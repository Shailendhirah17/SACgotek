import urllib.request
import json
import ssl

def main():
    print("Fetching fonts...")
    urls = [
        "https://cdn.jsdelivr.net/gh/hasinhayder/google-fonts/fonts.json",
        "https://raw.githubusercontent.com/hasinhayder/google-fonts/master/fonts.json"
    ]
    
    # Disable SSL certificate verification
    ctx = ssl.create_default_context()
    ctx.check_hostname = False
    ctx.verify_mode = ssl.CERT_NONE

    fonts = set()
    for url in urls:
        try:
            req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
            with urllib.request.urlopen(req, context=ctx) as response:
                data = json.loads(response.read().decode('utf-8'))
                if isinstance(data, dict) and 'fonts' in data:
                    for f in data['fonts']:
                        fonts.add(f)
                elif isinstance(data, list):
                    for f in data:
                        if isinstance(f, str):
                            fonts.add(f)
                        elif isinstance(f, dict) and 'family' in f:
                            fonts.add(f['family'])
            print(f"Loaded from {url}, total unique fonts: {len(fonts)}")
        except Exception as e:
            print(f"Error fetching from {url}: {e}")

    # Let's also fetch another public list of Google Fonts to ensure we get as many as possible.
    try:
        # Fetching a known large list of Google Fonts
        url = "https://raw.githubusercontent.com/jonathantneal/google-fonts-complete/master/fonts.json"
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, context=ctx) as response:
            data = json.loads(response.read().decode('utf-8'))
            if isinstance(data, dict):
                for f in data.keys():
                    fonts.add(f)
        print(f"Loaded from jonathantneal, total unique fonts: {len(fonts)}")
    except Exception as e:
        print(f"Error fetching from jonathantneal: {e}")

    # Let's save the list
    font_list = sorted(list(fonts))
    print(f"Total Google fonts retrieved: {len(font_list)}")
    
    with open("retrieved_fonts.json", "w") as f:
        json.dump(font_list, f, indent=2)

if __name__ == "__main__":
    main()
