const API_BASE = '/api.php';
let studentsList = [];

document.addEventListener('DOMContentLoaded', () => {
    initNavigation();
    loadDashboard();
    loadStudentsList(); // For dropdowns
});

function initNavigation() {
    document.querySelectorAll('.nav-item').forEach(nav => {
        nav.addEventListener('click', () => {
            const section = nav.dataset.section;
            if(!section) return;
            
            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
            nav.classList.add('active');
            
            showSection(section);
            document.getElementById('pageTitle').textContent = nav.textContent.trim();
        });
    });

    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', (e) => {
            const siblings = e.target.parentElement.querySelectorAll('.tab');
            siblings.forEach(s => s.classList.remove('active'));
            e.target.classList.add('active');
            
            if(e.target.parentElement.id === 'achieveTabs') {
                loadAchievements(e.target.dataset.atype);
            } else {
                loadActivities(e.target.dataset.filter);
            }
        });
    });
}

function showSection(id) {
    document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
    document.getElementById(`sec-${id}`).classList.add('active');
    
    switch(id) {
        case 'dashboard': loadDashboard(); break;
        case 'profiles': loadStudents(); break;
        case 'behavior': loadBehavior(); break;
        case 'activities': loadActivities('all'); break;
        case 'academics': loadAcademics(); break;
        case 'achievements': loadAchievements(''); break;
        case 'communication': loadCommunication(); break;
        case 'library': loadLibrary(); break;
        case 'fees': loadFees(); break;
        case 'engagement': loadEngagement(); break;
        case 'search': document.getElementById('smartSearchInput').focus(); break;
    }
}

async function fetchAPI(endpoint, options = {}) {
    try {
        const res = await fetch(`${API_BASE}/${endpoint}`, options);
        if(!res.ok) throw new Error('API Error');
        return await res.json();
    } catch(e) {
        console.error(e);
        return null;
    }
}

function getInitials(name) {
    return name ? name.split(' ').map(n=>n[0]).join('').substring(0,2).toUpperCase() : 'ST';
}

function badge(text, type) {
    const cls = type ? `badge-${type}` : 'badge-good';
    return `<span class="badge ${cls}">${text}</span>`;
}

// --- Dashboard ---
async function loadDashboard() {
    const data = await fetchAPI('stats');
    if(!data) return;

    document.getElementById('statsGrid').innerHTML = `
        <div class="stat-card">
            <div class="stat-glow purple"></div>
            <div class="stat-icon purple"><i class="fas fa-users"></i></div>
            <div class="stat-value">${data.totalStudents}</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-glow green"></div>
            <div class="stat-icon green"><i class="fas fa-book"></i></div>
            <div class="stat-value">${data.totalBooks}</div>
            <div class="stat-label">Library Books</div>
        </div>
        <div class="stat-card">
            <div class="stat-glow blue"></div>
            <div class="stat-icon blue"><i class="fas fa-trophy"></i></div>
            <div class="stat-value">${data.achievementStats.reduce((a,b)=>a+b.count,0)}</div>
            <div class="stat-label">Total Achievements</div>
        </div>
        <div class="stat-card">
            <div class="stat-glow orange"></div>
            <div class="stat-icon orange"><i class="fas fa-heart"></i></div>
            <div class="stat-value">${data.behaviorStats.reduce((a,b)=>a+b.count,0)}</div>
            <div class="stat-label">Behavior Records</div>
        </div>
    `;

    document.getElementById('recentBehaviors').innerHTML = data.recentBehaviors.map(b => `
        <div class="timeline-item">
            <div class="time">${b.reported_date}</div>
            <div>
                <strong>${b.full_name}</strong>
                <p style="font-size:12px;color:var(--text2);margin-top:2px">${b.remarks} ${badge(b.behavior_type, b.behavior_type)}</p>
            </div>
        </div>
    `).join('') || '<p style="color:var(--text2);font-size:13px">No records found</p>';

    document.getElementById('recentAchievements').innerHTML = data.recentAchievements.map(a => `
        <div class="timeline-item">
            <div class="time">${a.achievement_date}</div>
            <div>
                <strong>${a.title}</strong>
                <p style="font-size:12px;color:var(--text2);margin-top:2px">${a.full_name} ${badge(a.participation_status, a.participation_status)}</p>
            </div>
        </div>
    `).join('') || '<p style="color:var(--text2);font-size:13px">No records found</p>';

    // Render CSS Charts
    renderBarChart('behaviorChart', data.behaviorStats, 'behavior_type');
    renderBarChart('activityChart', data.activityStats, 'activity_type');
}

function renderBarChart(id, data, labelKey) {
    const max = Math.max(...data.map(d => d.count), 1);
    const html = data.map(d => {
        const pct = (d.count / max) * 100;
        let color = 'var(--accent)';
        if(d[labelKey] === 'good' || d[labelKey] === 'sports') color = 'var(--green)';
        if(d[labelKey] === 'misbehavior' || d[labelKey] === 'inactive') color = 'var(--red)';
        
        return `
        <div class="chart-col" title="${d.count}">
            <div class="bar" style="height:${pct}%;background:${color}"></div>
            <span>${d[labelKey].substring(0,3).toUpperCase()}</span>
        </div>`;
    }).join('');
    document.getElementById(id).innerHTML = html;
}

// --- Students ---
async function loadStudents() {
    document.getElementById('studentsTable').innerHTML = '<tr><td colspan="8" align="center"><div class="spinner"></div></td></tr>';
    const data = await fetchAPI('students');
    if(!data) return;
    studentsList = data; // Cache
    populateDropdowns();
    
    document.getElementById('studentsTable').innerHTML = data.map(s => `
        <tr>
            <td>#${s.id}</td>
            <td>
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:32px;height:32px;border-radius:50%;background:var(--card2);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700">${getInitials(s.full_name)}</div>
                    <strong>${s.full_name}</strong>
                </div>
            </td>
            <td>${s.admission_no || '-'}</td>
            <td>${s.class_name || '-'}</td>
            <td>${s.section_name || '-'}</td>
            <td>${s.email || '-'}</td>
            <td>${s.mobile || '-'}</td>
            <td><button class="btn btn-sm btn-ghost" onclick="viewStudent(${s.id})">View Profile</button></td>
        </tr>
    `).join('');
}

async function loadStudentsList() {
    const data = await fetchAPI('students');
    if(data) {
        studentsList = data;
        populateDropdowns();
    }
}

function populateDropdowns() {
    const options = studentsList.map(s => `<option value="${s.id}">${s.full_name} (${s.admission_no||'-'})</option>`).join('');
    ['bm_student', 'am_student', 'acm_student'].forEach(id => {
        const el = document.getElementById(id);
        if(el) el.innerHTML = options;
    });
}

// --- Student Detail ---
async function viewStudent(id) {
    showSection('studentDetail');
    document.getElementById('studentProfile').innerHTML = '<div class="loading"><div class="spinner"></div></div>';
    
    const data = await fetchAPI(`student/${id}?id=${id}`);
    if(!data) return;
    
    const p = data.profile;
    
    let html = `
        <div class="profile-header">
            <div class="profile-avatar">${getInitials(p.full_name)}</div>
            <div class="profile-info">
                <h2>${p.full_name}</h2>
                <p>Admission: ${p.admission_no || '-'} | Class: ${p.class_name||'-'} - ${p.section_name||'-'}</p>
                <div class="profile-meta">
                    <span><i class="fas fa-envelope"></i> ${p.email || '-'}</span>
                    <span><i class="fas fa-phone"></i> ${p.mobile || '-'}</span>
                    <span><i class="fas fa-birthday-cake"></i> ${p.date_of_birth || '-'}</span>
                </div>
            </div>
        </div>
        
        <div class="grid-2">
            <div class="card">
                <div class="card-header"><h3>Recent Behaviors</h3></div>
                ${data.behaviors.slice(0,5).map(b => `
                    <div class="timeline-item">
                        <div class="time">${b.reported_date}</div>
                        <div><strong>${b.category}</strong> <p style="font-size:12px;color:var(--text2)">${b.remarks} ${badge(b.behavior_type, b.behavior_type)}</p></div>
                    </div>
                `).join('') || '<p style="font-size:13px;color:var(--text2)">No records</p>'}
            </div>
            
            <div class="card">
                <div class="card-header"><h3>Achievements</h3></div>
                ${data.achievements.slice(0,5).map(a => `
                    <div class="timeline-item">
                        <div class="time">${a.achievement_date}</div>
                        <div><strong>${a.title}</strong> <p style="font-size:12px;color:var(--text2)">${a.description} ${badge(a.participation_status, a.participation_status)}</p></div>
                    </div>
                `).join('') || '<p style="font-size:13px;color:var(--text2)">No records</p>'}
            </div>
        </div>
        
        <div class="grid-2">
            <div class="card">
                <div class="card-header"><h3>Activities</h3></div>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>Activity</th><th>Type</th><th>Level</th></tr></thead>
                        <tbody>
                            ${data.activities.map(a => `<tr><td>${a.activity_name}</td><td>${badge(a.activity_type, a.activity_type)}</td><td>${a.skill_level}</td></tr>`).join('') || '<tr><td colspan="3">None</td></tr>'}
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header"><h3>Fee & Spending</h3></div>
                <div style="font-size:24px;font-weight:700;color:var(--green);margin-bottom:12px">$${data.fees?.total_paid || 0} Paid</div>
                <h4>Spending by Category</h4>
                <div style="margin-top:10px">
                    ${data.spending.map(s => `
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;font-size:13px">
                            <span style="text-transform:capitalize">${s.category}</span>
                            <strong>$${s.total}</strong>
                        </div>
                    `).join('') || '<p style="font-size:13px;color:var(--text2)">No spending</p>'}
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('studentProfile').innerHTML = html;
}

// --- Loaders ---
async function loadBehavior() {
    const data = await fetchAPI('behaviors');
    if(!data) return;
    document.getElementById('behaviorTable').innerHTML = data.map(b => `
        <tr>
            <td><strong>${b.full_name}</strong></td>
            <td>${badge(b.behavior_type, b.behavior_type)}</td>
            <td style="text-transform:capitalize">${b.category}</td>
            <td>${b.remarks}</td>
            <td>${b.reported_by}</td>
            <td>${b.reported_date}</td>
        </tr>
    `).join('');
}

async function loadActivities(filter) {
    const data = await fetchAPI(`activities${filter!=='all'?`?type=${filter}`:''}`);
    if(!data) return;
    document.getElementById('activitiesTable').innerHTML = data.map(a => `
        <tr>
            <td><strong>${a.full_name}</strong></td>
            <td>${badge(a.activity_type, a.activity_type)}</td>
            <td>${a.activity_name}</td>
            <td style="text-transform:capitalize">${a.skill_level}</td>
            <td>${a.notes}</td>
        </tr>
    `).join('');
}

async function loadAchievements(type) {
    const data = await fetchAPI(`achievements${type?`?type=${type}`:''}`);
    if(!data) return;
    document.getElementById('achievementsTable').innerHTML = data.map(a => `
        <tr>
            <td><strong>${a.full_name}</strong></td>
            <td>${badge(a.achievement_type, a.achievement_type)}</td>
            <td><strong>${a.title}</strong></td>
            <td>${a.description}</td>
            <td>${badge(a.participation_status, a.participation_status)}</td>
            <td>${a.achievement_date}</td>
        </tr>
    `).join('');
}

async function loadAcademics() {
    const data = await fetchAPI('academics');
    if(!data) return;
    document.getElementById('academicsTable').innerHTML = data.map(a => {
        const avg = parseFloat(a.avg_marks).toFixed(1);
        let color = avg > 80 ? 'green' : (avg > 60 ? 'purple' : 'red');
        return `
        <tr>
            <td><strong>${a.full_name}</strong></td>
            <td>${a.admission_no || '-'}</td>
            <td style="font-size:18px;font-weight:700;color:var(--${color})">${avg}%</td>
            <td style="width:200px">
                <div class="progress-bar"><div class="progress-fill ${color}" style="width:${avg}%"></div></div>
            </td>
        </tr>
    `}).join('');
}

async function loadCommunication() {
    const data = await fetchAPI('communications');
    if(!data) return;
    document.getElementById('commTable').innerHTML = data.map(c => `
        <tr>
            <td style="text-transform:capitalize"><i class="fas fa-${c.channel==='email'?'envelope':(c.channel==='sms'?'sms':'users')}" style="color:var(--accent);margin-right:6px"></i> ${c.channel}</td>
            <td>${c.full_name || '<strong>All Students</strong>'}</td>
            <td><strong>${c.subject}</strong></td>
            <td>${badge(c.event_type.replace('_',' '), c.event_type==='sports'?'sports':'interested')}</td>
            <td>${c.sent_by}</td>
            <td>${c.sent_at.split(' ')[0]}</td>
        </tr>
    `).join('');
}

async function loadLibrary() {
    const data = await fetchAPI('library');
    if(!data) return;
    document.getElementById('booksTable').innerHTML = data.books.map(b => `
        <tr>
            <td><strong>${b.book_title}</strong></td>
            <td>${b.author_name}</td>
            <td>${b.isbn_no || '-'}</td>
            <td>${b.quantity}</td>
            <td><span class="badge ${b.pending_count>0?'badge-misbehavior':'badge-good'}">${b.pending_count}</span></td>
        </tr>
    `).join('');
    
    document.getElementById('issuesTable').innerHTML = data.issues.map(i => `
        <tr>
            <td><strong>${i.book_title}</strong><br><span style="font-size:11px;color:var(--text2)">${i.author_name}</span></td>
            <td>${i.given_date}</td>
            <td>${i.due_date}</td>
            <td>${i.issue_status==='I'?badge('Issued', 'average'):badge('Returned', 'good')}</td>
        </tr>
    `).join('');
}

async function loadFees() {
    const data = await fetchAPI('fees');
    if(!data) return;
    document.getElementById('feesTable').innerHTML = data.map(f => `
        <tr>
            <td><strong>${f.full_name}</strong></td>
            <td>${f.admission_no || '-'}</td>
            <td style="color:var(--green);font-weight:700">$${f.total_paid}</td>
            <td style="color:var(--orange);font-weight:700">$${f.total_spent}</td>
            <td style="color:var(--accent2);font-weight:700;font-size:16px">$${parseFloat(f.total_paid) + parseFloat(f.total_spent)}</td>
        </tr>
    `).join('');
}

async function loadEngagement() {
    const data = await fetchAPI('engagement');
    if(!data) return;
    document.getElementById('engagementTable').innerHTML = data.map(e => `
        <tr>
            <td><strong>${e.full_name}</strong></td>
            <td>${e.behavior_count}</td>
            <td>${e.activity_count}</td>
            <td>${e.achievement_count}</td>
            <td>${e.present_count}</td>
            <td>
                <div class="score-circle ${e.status==='highly_active'?'score-high':(e.status==='active'?'score-mid':'score-low')}">${e.engagement_score}</div>
            </td>
            <td>${badge(e.status.replace('_',' '), e.status==='highly_active'?'good':(e.status==='active'?'average':'misbehavior'))}</td>
        </tr>
    `).join('');
}

// --- Search ---
let searchTimer;
function handleGlobalSearch(e) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        if(e.target.value.length > 2) {
            showSection('search');
            document.getElementById('smartSearchInput').value = e.target.value;
            doSmartSearch();
        }
    }, 500);
}

function handleSmartSearch(e) {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        if(e.target.value.length > 2) doSmartSearch();
        else document.getElementById('searchResults').innerHTML = '';
    }, 500);
}

async function doSmartSearch() {
    const q = document.getElementById('smartSearchInput').value;
    const filter = document.getElementById('searchFilter').value;
    if(!q || q.length < 3) return;
    
    document.getElementById('searchResults').innerHTML = '<div class="spinner"></div>';
    const data = await fetchAPI(`search?q=${encodeURIComponent(q)}&filter=${filter}`);
    
    if(!data || data.length === 0) {
        document.getElementById('searchResults').innerHTML = '<p>No results found</p>';
        return;
    }
    
    document.getElementById('searchResults').innerHTML = '<div class="table-wrap"><table><thead><tr><th>Type</th><th>Student</th><th>Details</th></tr></thead><tbody>' + data.map(r => {
        let details = '';
        let typeBadge = '';
        if(r.result_type === 'student') {
            typeBadge = badge('Student', 'active');
            details = `Admission: ${r.admission_no} | Email: ${r.email}`;
        } else if(r.result_type === 'behavior') {
            typeBadge = badge('Behavior', 'average');
            details = `[${r.behavior_type.toUpperCase()}] ${r.category}: ${r.remarks}`;
        } else if(r.result_type === 'achievement') {
            typeBadge = badge('Achievement', 'prize');
            details = `[${r.achievement_type.toUpperCase()}] ${r.title}`;
        } else if(r.result_type === 'activity') {
            typeBadge = badge('Activity', 'extracurricular');
            details = `[${r.activity_type.toUpperCase()}] ${r.activity_name}`;
        }
        
        return `
            <tr>
                <td>${typeBadge}</td>
                <td><strong>${r.full_name}</strong></td>
                <td>${details}</td>
            </tr>
        `;
    }).join('') + '</tbody></table></div>';
}

// --- Modals & Forms ---
function openModal(id) { document.getElementById(id).classList.add('show'); }
function closeModal(id) { document.getElementById(id).classList.remove('show'); }

async function submitBehavior() {
    const data = {
        student_id: document.getElementById('bm_student').value,
        behavior_type: document.getElementById('bm_type').value,
        category: document.getElementById('bm_cat').value,
        remarks: document.getElementById('bm_remarks').value
    };
    await fetchAPI('behaviors', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    closeModal('behaviorModal');
    loadBehavior();
    loadDashboard();
}

async function submitActivity() {
    const data = {
        student_id: document.getElementById('am_student').value,
        activity_type: document.getElementById('am_type').value,
        activity_name: document.getElementById('am_name').value,
        skill_level: document.getElementById('am_level').value
    };
    await fetchAPI('activities', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    closeModal('activityModal');
    loadActivities('all');
}

async function submitAchievement() {
    const data = {
        student_id: document.getElementById('acm_student').value,
        achievement_type: document.getElementById('acm_type').value,
        title: document.getElementById('acm_title').value,
        description: document.getElementById('acm_desc').value,
        participation_status: document.getElementById('acm_status').value
    };
    await fetchAPI('achievements', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    closeModal('achievementModal');
    loadAchievements('');
}

async function submitComm() {
    const data = {
        channel: document.getElementById('cm_channel').value,
        event_type: document.getElementById('cm_event').value,
        subject: document.getElementById('cm_subject').value,
        message: document.getElementById('cm_message').value
    };
    await fetchAPI('communications', { method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify(data) });
    closeModal('commModal');
    loadCommunication();
}
