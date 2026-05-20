import paramiko
import os

hostname = '193.202.45.164'
port = 65002
username = 'u841409365'
password = 'Eash@2005'

subdomains = [
    'domains/test1-technosprint.online/public_html/test-sacgotek/',
    'domains/test-technoprint.online/public_html/erpv2/'
]

files = [
    'app/Http/Controllers/UltraSuperAdmin/DashboardController.php',
    'resources/views/backEnd/ultraSuperAdmin/dashboard.blade.php',
    'resources/views/backEnd/ultraSuperAdmin/layouts/sidebar.blade.php',
    'resources/views/backEnd/ultraSuperAdmin/layouts/header.blade.php',
    'resources/views/backEnd/ultraSuperAdmin/layouts/master.blade.php',
    'resources/views/backEnd/ultraSuperAdmin/settings/index.blade.php',
    'app/Http/Controllers/Auth/UltraSuperAdminLoginController.php',
    'app/Http/Middleware/UltraSuperAdminMiddleware.php'
]

print("Connecting to SSH via Paramiko...")
ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())

try:
    ssh.connect(hostname, port=port, username=username, password=password)
    
    for remote_dir in subdomains:
        print(f"\n🌐 Deploying to: {remote_dir}")
        print("-" * 50)
        sftp = ssh.open_sftp()
        
        for file_path in files:
            local_path = file_path.replace('/', os.sep)
            normalized_remote = file_path.replace('\\', '/')
            remote_path = f"{remote_dir}{normalized_remote}"
            
            # Ensure remote directory structure exists
            remote_file_dir = os.path.dirname(normalized_remote)
            if remote_file_dir:
                path_parts = remote_file_dir.split('/')
                current_path = remote_dir.rstrip('/')
                for part in path_parts:
                    current_path = f"{current_path}/{part}"
                    try:
                        sftp.stat(current_path)
                    except IOError:
                        try:
                            sftp.mkdir(current_path)
                            print(f"  Created remote directory: {current_path}")
                        except Exception as ex:
                            print(f"  Failed to create directory {current_path}: {str(ex)}")
            
            print(f"Uploading {file_path} to {remote_path}...")
            try:
                if os.path.exists(local_path):
                    sftp.put(local_path, remote_path)
                    print(f"  [SUCCESS]")
                else:
                    print(f"  [FAILED] Local file NOT FOUND: {local_path}")
            except Exception as e:
                print(f"  [FAILED] SFTP Put Error: {str(e)}")
        
        sftp.close()
        
        print(f"\nExecuting maintenance commands for {remote_dir}...")
        commands = [
            f"cd {remote_dir} && php artisan view:clear",
            f"cd {remote_dir} && php artisan route:clear",
            f"cd {remote_dir} && php artisan config:clear",
            f"cd {remote_dir} && php artisan cache:clear"
        ]
        
        for cmd in commands:
            print(f"Running: {cmd}")
            stdin, stdout, stderr = ssh.exec_command(cmd)
            stdout.channel.recv_exit_status()
            print("  Done.")
            
    print("\nDeployment completed successfully to all subdomains!")
finally:
    ssh.close()
