@extends('backEnd.master')
@section('title')
    Resume Creator
@endsection

@section('mainContent')
@push('css')
<link href="https://fonts.googleapis.com/css2?family=Georgia&family=Inter:wght@300;400;600;700&family=Merriweather:wght@300;400;700&family=Montserrat:wght@300;400;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<style>
    /* Admin UI Layout Adjustments */
    .resume-creator-wrapper {
        display: flex;
        gap: 30px;
        margin-top: 20px;
        align-items: flex-start;
    }
    
    .resume-control-panel {
        width: 320px;
        background: #ffffff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 100px;
        z-index: 10;
    }
    
    .resume-preview-container {
        flex: 1;
        display: flex;
        justify-content: center;
        background: #f0f2f5;
        padding: 40px 20px;
        border-radius: 12px;
        box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.03);
        min-height: 1000px;
    }

    /* Core Resume Sheet Styling (Simulating A4 Page) */
    .resume-sheet {
        width: 800px;
        min-height: 1130px; /* A4 aspect ratio height */
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 50px;
        box-sizing: border-box;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    /* Control Panel Items */
    .control-section {
        margin-bottom: 25px;
        border-bottom: 1px solid #f1f3f7;
        padding-bottom: 15px;
    }
    
    .control-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .control-title {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .template-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }
    
    .template-btn {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
        cursor: pointer;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .template-btn:hover {
        border-color: #a0aec0;
    }
    
    .template-btn.active {
        background: #ebf8ff;
        border-color: #3182ce;
        color: #2b6cb0;
    }
    
    .color-presets {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 10px;
    }
    
    .color-swatch {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 1px #cbd5e0;
        transition: transform 0.15s ease;
    }
    
    .color-swatch:hover {
        transform: scale(1.15);
    }
    
    .color-swatch.active {
        box-shadow: 0 0 0 2px #4299e1;
    }
    
    .custom-color-input {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
    }
    
    .custom-color-input input[type="color"] {
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        cursor: pointer;
        background: none;
    }
    
    .font-select, .size-select {
        width: 100%;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e0;
        background: #ffffff;
        font-size: 14px;
        color: #4a5568;
    }

    /* Autocomplete / Searchable Dropdown Styles */
    .searchable-font-container {
        position: relative;
        width: 100%;
    }
    .custom-dropdown-list {
        scrollbar-width: thin;
        box-sizing: border-box;
    }
    .custom-dropdown-list::-webkit-scrollbar {
        width: 6px;
    }
    .custom-dropdown-list::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 3px;
    }
    .font-dropdown-item {
        padding: 8px 12px;
        cursor: pointer;
        font-size: 13px;
        color: #4a5568;
        transition: background 0.15s ease, color 0.15s ease;
        text-align: left;
    }
    .font-dropdown-item:hover {
        background: #ebf8ff;
        color: #2b6cb0;
    }
    .font-dropdown-item.active {
        background: #bee3f8;
        color: #2b6cb0;
        font-weight: 600;
    }

    /* 5,000+ Presets Color Spectrum Mosaic Styles */
    .color-category-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 8px;
    }
    .color-tab {
        background: #edf2f7;
        color: #4a5568;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.15s ease;
        user-select: none;
    }
    .color-tab:hover {
        background: #cbd5e0;
    }
    .color-tab.active {
        background: #3182ce;
        color: #ffffff;
    }
    .mosaic-cell {
        aspect-ratio: 1;
        cursor: pointer;
        border-radius: 2px;
        transition: transform 0.1s ease, box-shadow 0.1s ease;
    }
    .mosaic-cell:hover {
        transform: scale(1.5);
        z-index: 10;
        box-shadow: 0 0 4px rgba(0,0,0,0.4);
    }

    /* Print Button Styling */
    .print-btn {
        background: linear-gradient(90deg, #3182ce, #2b6cb0);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        width: 100%;
        padding: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 6px rgba(43, 108, 176, 0.2);
        transition: all 0.2s ease;
    }
    
    .print-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 12px rgba(43, 108, 176, 0.3);
    }

    /* Real-time Content Editable Styling */
    [contenteditable="true"] {
        outline: none;
        border-radius: 3px;
        transition: background-color 0.2s ease;
    }
    
    [contenteditable="true"]:hover {
        background-color: rgba(66, 153, 225, 0.08);
        box-shadow: 0 0 0 1px rgba(66, 153, 225, 0.2);
    }
    
    [contenteditable="true"]:focus {
        background-color: rgba(66, 153, 225, 0.12);
        box-shadow: 0 0 0 2px rgba(66, 153, 225, 0.4);
    }

    /* ==========================================
       RESUME TEMPLATE DESIGNS & THEMES
       ========================================== */

    /* Template Accent Color Variable helper */
    :root {
        --resume-accent: #3182ce;
        --resume-accent-rgb: 49, 130, 206;
    }

    /* Font Sizes Setup */
    .size-small { font-size: 13px; }
    .size-small h1 { font-size: 24px; }
    .size-small h2 { font-size: 16px; }
    .size-small h3 { font-size: 14px; }

    .size-medium { font-size: 15px; }
    .size-medium h1 { font-size: 30px; }
    .size-medium h2 { font-size: 20px; }
    .size-medium h3 { font-size: 16px; }

    .size-large { font-size: 17px; }
    .size-large h1 { font-size: 36px; }
    .size-large h2 { font-size: 24px; }
    .size-large h3 { font-size: 18px; }

    /* Templates Rules */
    
    /* 1. MODERN EXECUTIVE (Top banner, Two Columns) */
    .t-modern {
        font-family: 'Inter', sans-serif;
        color: #2d3748;
    }
    .t-modern .r-header {
        border-left: 6px solid var(--resume-accent);
        padding-left: 20px;
        margin-bottom: 30px;
    }
    .t-modern .r-name {
        font-weight: 700;
        letter-spacing: -1px;
        color: #1a202c;
    }
    .t-modern .r-title {
        color: var(--resume-accent);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .t-modern .r-section-title {
        border-bottom: 2px solid #edf2f7;
        padding-bottom: 6px;
        margin-bottom: 15px;
        color: #1a202c;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .t-modern .r-section-title::after {
        content: '';
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, var(--resume-accent), transparent);
    }
    .t-modern .r-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    /* 2. MINIMALIST CREATIVE (Spacious layout, Clean Rules) */
    .t-minimal {
        font-family: 'Montserrat', sans-serif;
        color: #4a5568;
        line-height: 1.6;
    }
    .t-minimal .r-header {
        text-align: right;
        margin-bottom: 40px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 20px;
    }
    .t-minimal .r-name {
        font-weight: 300;
        text-transform: uppercase;
        letter-spacing: 3px;
        color: #1a202c;
    }
    .t-minimal .r-title {
        font-weight: 500;
        color: var(--resume-accent);
    }
    .t-minimal .r-section-title {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: #2d3748;
        margin-bottom: 20px;
        position: relative;
    }
    .t-minimal .r-section-title span {
        background: #ffffff;
        padding-right: 15px;
        position: relative;
        z-index: 1;
    }
    .t-minimal .r-section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: #e2e8f0;
        z-index: 0;
    }
    .t-minimal .r-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 30px;
    }

    /* 3. CLASSIC ACADEMIC (Serif, Centered headers) */
    .t-classic {
        font-family: 'Georgia', serif;
        color: #1a202c;
    }
    .t-classic .r-header {
        text-align: center;
        margin-bottom: 35px;
    }
    .t-classic .r-name {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: #111;
        margin-bottom: 5px;
    }
    .t-classic .r-title {
        font-style: italic;
        color: #4a5568;
    }
    .t-classic .r-contact {
        justify-content: center;
        border-top: 1px solid #718096;
        border-bottom: 1px solid #718096;
        padding: 8px 0;
        margin-top: 15px;
    }
    .t-classic .r-section-title {
        text-align: center;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        border-bottom: 1px double #4a5568;
        padding-bottom: 4px;
        margin-bottom: 18px;
        margin-top: 25px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .t-classic .r-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 25px;
    }

    /* 4. ELEGANT COMPACT (Splitted Sidebar Page) */
    .t-compact {
        font-family: 'Merriweather', serif;
        color: #2d3748;
        padding: 0 !important;
    }
    .t-compact .r-main-container {
        display: grid;
        grid-template-columns: 260px 1fr;
        min-height: 1130px;
    }
    .t-compact .r-left-bar {
        background: #2d3748;
        color: #f7fafc;
        padding: 40px 25px;
    }
    .t-compact .r-left-bar [contenteditable="true"]:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    .t-compact .r-left-bar .r-section-title {
        color: #ffffff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    .t-compact .r-right-bar {
        padding: 40px 35px;
        background: #ffffff;
    }
    .t-compact .r-name {
        font-weight: 700;
        color: #ffffff;
    }
    .t-compact .r-right-bar .r-name {
        color: #1a202c;
    }
    .t-compact .r-title {
        color: var(--resume-accent);
        font-weight: 600;
    }
    .t-compact .r-section-title {
        font-size: 16px;
        font-weight: 700;
        color: #1a202c;
        border-bottom: 2px solid var(--resume-accent);
        padding-bottom: 4px;
        margin-bottom: 15px;
        margin-top: 25px;
    }
    .t-compact .r-contact {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    /* 5. SLEEK PROFESSIONAL (Banner color header) */
    .t-sleek {
        font-family: 'Roboto', sans-serif;
        color: #333333;
        padding: 0 !important;
    }
    .t-sleek .r-banner {
        background: var(--resume-accent);
        color: #ffffff;
        padding: 40px 50px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .t-sleek .r-banner [contenteditable="true"]:hover {
        background-color: rgba(255, 255, 255, 0.15);
    }
    .t-sleek .r-name {
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 5px;
    }
    .t-sleek .r-title {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 400;
        letter-spacing: 1px;
    }
    .t-sleek .r-content-area {
        padding: 40px 50px;
    }
    .t-sleek .r-section-title {
        font-weight: 700;
        color: #111111;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-transform: uppercase;
        font-size: 16px;
    }
    .t-sleek .r-section-title::before {
        content: '';
        display: inline-block;
        width: 10px;
        height: 10px;
        background: var(--resume-accent);
        border-radius: 50%;
    }
    .t-sleek .r-grid {
        display: grid;
        grid-template-columns: 1.8fr 1.2fr;
        gap: 30px;
    }

    /* Generic Utility Items for Preview */
    .r-contact {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-top: 10px;
        font-size: 13px;
        color: #718096;
    }
    .t-compact .r-contact {
        color: #cbd5e0;
    }
    
    .r-contact-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .resume-section {
        margin-bottom: 25px;
    }
    
    .timeline-item {
        margin-bottom: 15px;
        position: relative;
        padding-left: 20px;
        border-left: 2px solid #e2e8f0;
    }
    
    .timeline-item-title {
        font-weight: 600;
        color: #2d3748;
    }
    .t-compact .timeline-item-title {
        color: #1a202c;
    }
    
    .timeline-item-meta {
        font-size: 12px;
        color: #a0aec0;
        margin-bottom: 5px;
    }
    
    .skills-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }
    
    .skill-badge {
        background: #edf2f7;
        color: #4a5568;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .t-compact .skill-badge {
        background: rgba(255, 255, 255, 0.1);
        color: #e2e8f0;
    }

    /* ==========================================
       PRINT LAYOUT RULES
       ========================================== */
    @media print {
        /* Reset and hide master admin layouts */
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        
        #main-content, .main-wrapper, .admin, .sidebar, #sidebar, .header_aria, .footer-area, .breadcrumb, .resume-control-panel, .card {
            display: none !important;
            visibility: hidden !important;
        }
        
        .resume-preview-container {
            background: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
        }
        
        .resume-sheet {
            width: 100% !important;
            height: 100% !important;
            min-height: auto !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            overflow: visible !important;
        }
        
        /* Ensure background colors actually print */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>
@endpush

<div class="container-fluid p-0">
    <div class="row">
        <div class="col-lg-12">
            <div class="main-title">
                <h3 class="mb-30">Automatic Resume Creator</h3>
            </div>
        </div>
    </div>

    <div class="resume-creator-wrapper">
        <!-- 1. LEFT CONTROL PANEL -->
        <div class="resume-control-panel">
            <!-- Template Selection -->
            <div class="control-section">
                <div class="control-title">
                    <span class="flaticon-resume"></span>
                    Choose Design Template
                </div>
                <div class="template-grid">
                    <div class="template-btn active" onclick="setTemplate('modern')">Modern Exec</div>
                    <div class="template-btn" onclick="setTemplate('minimal')">Minimalist</div>
                    <div class="template-btn" onclick="setTemplate('classic')">Classic Acad</div>
                    <div class="template-btn" onclick="setTemplate('compact')">Two-Column</div>
                    <div class="template-btn" onclick="setTemplate('sleek')">Sleek Banner</div>
                </div>
            </div>

            <!-- Typography & Font Selector (Searchable Dropdown) -->
            <div class="control-section" style="position: relative;">
                <div class="control-title">
                    <span class="flaticon-book"></span>
                    Typography Font Style (2,000+ Google Fonts)
                </div>
                <div class="searchable-font-container">
                    <input type="text" id="font-search-input" placeholder="Search 2,000+ fonts..." onfocus="showFontDropdown()" oninput="filterFonts(this.value)" class="font-select" autocomplete="off" style="padding-right: 30px;">
                    <span id="font-clear-btn" onclick="clearFontSearch()" style="position: absolute; right: 10px; top: 38px; cursor: pointer; color: #cbd5e0; display: none; font-size: 18px; font-weight: bold; line-height: 1;">&times;</span>
                    
                    <div id="font-dropdown-list" class="custom-dropdown-list" style="display:none; max-height:260px; overflow-y:auto; border:1px solid #cbd5e0; border-radius:8px; position:absolute; background:white; width:100%; z-index:1000; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); margin-top: 4px;">
                        <div style="padding: 8px 12px; font-size:12px; color:#a0aec0; background:#f7fafc; border-bottom:1px solid #edf2f7;">Loading dynamic fonts...</div>
                    </div>
                </div>
            </div>

            <!-- Sizing Options -->
            <div class="control-section">
                <div class="control-title">
                    <span class="flaticon-calendar-1"></span>
                    Text Sizing
                </div>
                <select class="size-select" onchange="setSize(this.value)">
                    <option value="small">Compact / Small</option>
                    <option value="medium" selected>Standard / Medium</option>
                    <option value="large">Spacious / Large</option>
                </select>
            </div>

            <!-- Accent Color Theme & 5,000+ Preset Rainbow Catalog -->
            <div class="control-section">
                <div class="control-title" style="margin-bottom: 6px;">
                    <span class="flaticon-reading"></span>
                    Accent Color Theme
                </div>
                
                <div class="custom-color-input" style="flex-direction: column; align-items: flex-start; gap: 4px; width: 100%; margin-bottom: 12px;">
                    <label style="font-size:12px; color:#718096; margin-bottom: 2px;">Enter ANY Format (HEX, RGB, HSL or CSS Name):</label>
                    <div style="display: flex; align-items: center; gap: 8px; width: 100%;">
                        <input type="text" id="custom-color-text" placeholder="e.g. #3182ce, rgb(49,130,206), blue" oninput="handleColorInput(this.value)" class="font-select" style="font-size: 12px; height: 36px; padding: 4px 10px;">
                        <span id="color-valid-indicator" style="width: 28px; height: 28px; border-radius: 50%; background: #3182ce; border: 2px solid #ffffff; box-shadow: 0 0 0 1px #cbd5e0; display: inline-block; flex-shrink: 0; transition: background 0.15s ease;"></span>
                    </div>
                    <div id="color-error-msg" style="font-size:10px; color:#e53e3e; display:none; margin-top:2px;">⚠️ Invalid CSS color format</div>
                </div>

                <!-- 5,000+ Presets Color Spectrum Catalog -->
                <div style="font-size: 12px; font-weight: 600; color: #2c3e50; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    🌈 5,000+ Default Color Library
                </div>
                
                <!-- Filter Category Tabs -->
                <div class="color-category-tabs">
                    <div class="color-tab active" onclick="filterColorCategory('all')">All</div>
                    <div class="color-tab" onclick="filterColorCategory('warm')">Warm</div>
                    <div class="color-tab" onclick="filterColorCategory('cool')">Cool</div>
                    <div class="color-tab" onclick="filterColorCategory('pastel')">Pastels</div>
                    <div class="color-tab" onclick="filterColorCategory('corporate')">Corporate</div>
                    <div class="color-tab" onclick="filterColorCategory('neon')">Neons</div>
                </div>
                
                <!-- Large Dense Mosaic Grid -->
                <div id="color-mosaic-container" style="height: 180px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px; background: #ffffff; display: grid; grid-template-columns: repeat(12, 1fr); gap: 2px; scrollbar-width: thin; box-sizing: border-box;">
                    <!-- JS will dynamically populate 5,000+ color cells! -->
                </div>
                
                <div style="font-size: 10px; color:#a0aec0; margin-top:6px; text-align:center;">
                    ✨ Click any swatch above to instantly apply.
                </div>
            </div>

            <!-- Print Actions -->
            <div class="control-section">
                <button class="print-btn" onclick="window.print()">
                    <span class="flaticon-printer"></span>
                    Print / Save to PDF
                </button>
                <div style="font-size: 11px; color:#a0aec0; margin-top:10px; text-align:center;">
                    💡 Tip: Set Layout to "Landscape" or "Portrait" and enable "Background Graphics" in the print settings for best results.
                </div>
            </div>
        </div>

        <!-- 2. RIGHT LIVE RESUME PREVIEW -->
        <div class="resume-preview-container">
            <div class="resume-sheet t-modern size-medium" id="resume-sheet">
                
                <!-- Dynamic Template Render Area -->
                <div id="resume-dynamic-content">
                    
                    <!-- Top header section -->
                    <div class="r-header">
                        <h1 class="r-name" id="field-name" contenteditable="true">{{ $student_detail->full_name }}</h1>
                        <div class="r-title" id="field-subtitle" contenteditable="true">Student / Aspiring Professional</div>
                        
                        <div class="r-contact">
                            <div class="r-contact-item">
                                <span class="r-contact-label">Email:</span>
                                <span id="field-email" contenteditable="true">{{ $student_detail->email }}</span>
                            </div>
                            <div class="r-contact-item">
                                <span class="r-contact-label">Phone:</span>
                                <span id="field-phone" contenteditable="true">{{ $student_detail->mobile ?? '+88012345678' }}</span>
                            </div>
                            <div class="r-contact-item">
                                <span class="r-contact-label">DOB:</span>
                                <span id="field-dob" contenteditable="true">{{ $student_detail->date_of_birth ? dateConvert($student_detail->date_of_birth) : 'N/A' }}</span>
                            </div>
                            <div class="r-contact-item">
                                <span class="r-contact-label">Roll No:</span>
                                <span id="field-roll" contenteditable="true">{{ $student_detail->roll_no ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Inner Two-Column Layout -->
                    <div class="r-grid" id="resume-grid-container">
                        <!-- Main Left Column -->
                        <div class="r-column-main">
                            
                            <!-- Objective/Summary Section -->
                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Professional Summary</span></h2>
                                <div id="field-summary" contenteditable="true">
                                    Highly motivated student currently enrolled in class {{ $student_detail->defaultClass->class->class_name ?? 'N/A' }} (Section {{ $student_detail->defaultClass->section->section_name ?? 'N/A' }}) at SACgotek. Possesses exceptional problem-solving skills, strong academic background, and a proven ability to collaborate in fast-paced school environments. Seeking an opportunity to apply academic training to real-world projects.
                                </div>
                            </div>

                            <!-- Education Section -->
                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Education Details</span></h2>
                                <div class="timeline-item">
                                    <div class="timeline-item-title" id="field-edu-title" contenteditable="true">Academic Student Record - Infix School</div>
                                    <div class="timeline-item-meta">
                                        Class: <span id="field-edu-class" contenteditable="true">{{ $student_detail->defaultClass->class->class_name ?? 'N/A' }}</span> |
                                        Section: <span id="field-edu-section" contenteditable="true">{{ $student_detail->defaultClass->section->section_name ?? 'N/A' }}</span> |
                                        Admission No: <span id="field-edu-admission" contenteditable="true">{{ $student_detail->admission_no }}</span>
                                    </div>
                                    <div class="timeline-item-desc" id="field-edu-desc" contenteditable="true">
                                        Consistently maintained an excellent academic and attendance record. Participated in extra-curricular activities, quizzes, and classroom routine challenges.
                                    </div>
                                </div>
                            </div>

                            <!-- Academic Timeline/Activities Section -->
                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Achievements & School Timeline</span></h2>
                                @forelse($timelines as $timeline)
                                    <div class="timeline-item">
                                        <div class="timeline-item-title" contenteditable="true">{{ $timeline->title }}</div>
                                        <div class="timeline-item-meta">{{ dateConvert($timeline->date) }}</div>
                                        <div class="timeline-item-desc" contenteditable="true">{{ $timeline->description }}</div>
                                    </div>
                                @empty
                                    <div class="timeline-item">
                                        <div class="timeline-item-title" contenteditable="true">School Merit Certificate</div>
                                        <div class="timeline-item-meta">May 2026</div>
                                        <div class="timeline-item-desc" contenteditable="true">Rewarded for outstanding performance in academic challenges and active class participation.</div>
                                    </div>
                                    <div class="timeline-item">
                                        <div class="timeline-item-title" contenteditable="true">Science & Technology Project Champion</div>
                                        <div class="timeline-item-meta">March 2026</div>
                                        <div class="timeline-item-desc" contenteditable="true">Developed a dynamic student portal automation prototype demonstrating innovative logic and flow.</div>
                                    </div>
                                @endforelse
                            </div>

                        </div>

                        <!-- Sidebar Right Column -->
                        <div class="r-column-side">
                            
                            <!-- Skills Section -->
                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Skills</span></h2>
                                <div class="skills-badges" id="field-skills" contenteditable="true">
                                    <span class="skill-badge">Analytical Thinking</span>
                                    <span class="skill-badge">Communication</span>
                                    <span class="skill-badge">Teamwork</span>
                                    <span class="skill-badge">Problem Solving</span>
                                    <span class="skill-badge">Quick Learning</span>
                                    <span class="skill-badge">Digital Literacy</span>
                                </div>
                            </div>

                            <!-- Additional Info -->
                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Personal Info</span></h2>
                                <div style="font-size: 13px; line-height: 1.8;">
                                    <strong>Gender:</strong> <span id="field-gender" contenteditable="true">{{ $student_detail->gender->base_setup_name ?? 'N/A' }}</span><br>
                                    <strong>Blood Group:</strong> <span id="field-blood" contenteditable="true">{{ $student_detail->bloodGroup->base_setup_name ?? 'N/A' }}</span><br>
                                    <strong>Category:</strong> <span id="field-category" contenteditable="true">{{ $student_detail->category->category_name ?? 'General' }}</span><br>
                                    <strong>Present Address:</strong> <div id="field-address" contenteditable="true" style="margin-top:4px;">{{ $student_detail->current_address ?? '123 School Avenue, Main City' }}</div>
                                </div>
                            </div>

                            <!-- Hobbies & Interests -->
                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Interests</span></h2>
                                <div id="field-hobbies" contenteditable="true" style="font-size: 13px; line-height: 1.8;">
                                    • Coding & Technology<br>
                                    • Chess & Strategy Games<br>
                                    • Academic Research & Reading<br>
                                    • Outdoor Sports
                                </div>
                            </div>

                        </div>
                    </div> <!-- End Grid -->

                </div> <!-- End Dynamic Area -->

            </div>
        </div>
    </div>
</div>

<script>
    // Global Styling Parameters
    let currentTemplate = 'modern';
    let currentFont = 'Inter';
    let currentSize = 'medium';
    let currentAccent = '#3182ce';

    // 2000+ Fonts Searchable Autocomplete Dropdown State
    let allFonts = [];
    const fallbackFonts = [
        "Inter", "Montserrat", "Georgia", "Merriweather", "Roboto", "Open Sans", "Lato", "Poppins", 
        "Oswald", "Raleway", "Ubuntu", "Nunito", "Playfair Display", "Lora", "Fira Sans", 
        "Quicksand", "PT Sans", "Josefin Sans", "Cabin", "Rubik", "Kanit", "Mulish", 
        "Inconsolata", "Work Sans", "Arvo", "Cinzel", "Maven Pro", "Dosis", "Comfortaa", "Pacifico"
    ];

    // Initialize Fonts and Color Presets on Load
    window.addEventListener('DOMContentLoaded', () => {
        initFonts();
        generatePaletteColors();
        filterColorCategory('all');
        
        // Populate custom color text box with initial value
        document.getElementById('custom-color-text').value = currentAccent;
        document.getElementById('color-valid-indicator').style.background = currentAccent;

        // Hide font dropdown on click outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.searchable-font-container')) {
                hideFontDropdown();
            }
        });
    });

    async function initFonts() {
        const dropdown = document.getElementById('font-dropdown-list');
        try {
            const response = await fetch('https://cdn.jsdelivr.net/gh/hasinhayder/google-fonts/fonts.json');
            const data = await response.json();
            if (data && data.fonts && data.fonts.length > 0) {
                allFonts = data.fonts;
            } else {
                allFonts = fallbackFonts;
            }
        } catch (err) {
            console.warn("Failed to fetch Google Fonts CDN, using high-quality local fallback list.", err);
            allFonts = fallbackFonts;
        }
        renderFontDropdown(allFonts);
        document.getElementById('font-search-input').value = currentFont;
    }

    function renderFontDropdown(fontsList) {
        const dropdown = document.getElementById('font-dropdown-list');
        dropdown.innerHTML = '';
        
        if (fontsList.length === 0) {
            dropdown.innerHTML = '<div style="padding: 8px 12px; font-size:12px; color:#a0aec0; text-align:center;">No fonts found</div>';
            return;
        }

        const limit = 150;
        const visibleFonts = fontsList.slice(0, limit);

        const fragment = document.createDocumentFragment();
        visibleFonts.forEach(font => {
            const item = document.createElement('div');
            item.className = 'font-dropdown-item' + (font === currentFont ? ' active' : '');
            item.style.fontFamily = `'${font}', sans-serif`;
            item.innerText = font;
            
            // Lazily load the font stylesheet on hover for smooth dynamic preview!
            item.onmouseenter = () => {
                loadGoogleFont(font);
            };
            
            item.onclick = () => {
                selectFont(font);
            };
            fragment.appendChild(item);
        });
        dropdown.appendChild(fragment);

        if (fontsList.length > limit) {
            const moreIndicator = document.createElement('div');
            moreIndicator.style.cssText = 'padding: 8px 12px; font-size:11px; color:#a0aec0; text-align:center; background:#f7fafc; border-top:1px solid #edf2f7; font-style:italic;';
            moreIndicator.innerText = `...and ${fontsList.length - limit} more fonts. Keep typing to search!`;
            dropdown.appendChild(moreIndicator);
        }
    }

    function showFontDropdown() {
        document.getElementById('font-dropdown-list').style.display = 'block';
    }

    function hideFontDropdown() {
        document.getElementById('font-dropdown-list').style.display = 'none';
    }

    function filterFonts(query) {
        query = query.toLowerCase().trim();
        const clearBtn = document.getElementById('font-clear-btn');
        clearBtn.style.display = query ? 'block' : 'none';

        const filtered = allFonts.filter(font => font.toLowerCase().includes(query));
        renderFontDropdown(filtered);
    }

    function clearFontSearch() {
        const input = document.getElementById('font-search-input');
        input.value = '';
        input.focus();
        filterFonts('');
    }

    function selectFont(fontName) {
        document.getElementById('font-search-input').value = fontName;
        hideFontDropdown();
        
        // Dynamically Inject dynamic stylesheet from Google Web Fonts API
        loadGoogleFont(fontName);
        
        // Set preview font-family
        setFont(fontName);
    }

    function loadGoogleFont(fontName) {
        const standardWebSafe = ['arial', 'helvetica', 'times new roman', 'georgia', 'courier new', 'verdana', 'tahoma', 'trebuchet ms'];
        if (standardWebSafe.includes(fontName.toLowerCase())) return;

        const linkId = 'gfont-' + fontName.toLowerCase().replace(/\s+/g, '-');
        if (!document.getElementById(linkId)) {
            const link = document.createElement('link');
            link.id = linkId;
            link.rel = 'stylesheet';
            link.href = `https://fonts.googleapis.com/css2?family=${encodeURIComponent(fontName)}:wght@300;400;500;600;700&display=swap`;
            document.head.appendChild(link);
        }
    }

    function setTemplate(templateName) {
        // Toggle Active Class in Control Panel Buttons
        const buttons = document.querySelectorAll('.template-btn');
        buttons.forEach(btn => {
            if (btn.innerText.toLowerCase().includes(templateName.replace('modern','exec').replace('classic','acad').replace('compact','column').replace('sleek','banner').substring(0, 4))) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });

        // Get Resume Sheet
        const sheet = document.getElementById('resume-sheet');
        
        // Remove all template classes
        sheet.classList.remove('t-modern', 't-minimal', 't-classic', 't-compact', 't-sleek');
        sheet.classList.add('t-' + templateName);
        
        currentTemplate = templateName;
        rebuildLayoutStructure();
    }

    function setFont(fontName) {
        const sheet = document.getElementById('resume-sheet');
        sheet.style.fontFamily = `'${fontName}', sans-serif`;
        currentFont = fontName;
    }

    function setSize(sizeName) {
        const sheet = document.getElementById('resume-sheet');
        sheet.classList.remove('size-small', 'size-medium', 'size-large');
        sheet.classList.add('size-' + sizeName);
        currentSize = sizeName;
    }

    // Accepting Custom Accent Colors in ANY Format Entry
    function isValidColor(colorString) {
        const s = new Option().style;
        s.color = colorString;
        return s.color !== '';
    }

    function handleColorInput(val) {
        val = val.trim();
        const indicator = document.getElementById('color-valid-indicator');
        const errorMsg = document.getElementById('color-error-msg');
        
        if (!val) {
            indicator.style.background = '#cbd5e0';
            errorMsg.style.display = 'none';
            return;
        }

        if (isValidColor(val)) {
            indicator.style.background = val;
            errorMsg.style.display = 'none';
            setAccent(val);
        } else {
            indicator.style.background = '#cbd5e0';
            errorMsg.style.display = 'block';
        }
    }

    function setAccent(colorHex) {
        // Set CSS Accent Variables dynamically
        document.documentElement.style.setProperty('--resume-accent', colorHex);
        currentAccent = colorHex;
        
        // Also update any inline styling accents if necessary
        const sectionTitles = document.querySelectorAll('.r-section-title');
        sectionTitles.forEach(title => {
            title.style.borderBottomColor = colorHex;
        });
    }

    // 5,000+ Presets Color Rainbow Spectrum Library
    let allPaletteColors = [];

    function generatePaletteColors() {
        const colors = [];
        // Loop Hues (0-360 in increments of 2.4 degrees = 150 unique hues)
        for (let h = 0; h < 360; h += 2.4) {
            const saturations = [30, 45, 60, 75, 90];
            const lightnesses = [25, 35, 45, 55, 65, 75, 85];
            
            saturations.forEach(s => {
                lightnesses.forEach(l => {
                    const hslStr = `hsl(${Math.round(h)}, ${s}%, ${l}%)`;
                    
                    // Categorize colors
                    let category = 'warm';
                    if (h >= 75 && h <= 250) {
                        category = 'cool';
                    }
                    if (l >= 70 && s >= 60) {
                        category = 'pastel';
                    } else if (l >= 25 && l <= 40 && s >= 30 && s <= 65) {
                        category = 'corporate';
                    } else if (s >= 90 && l >= 45 && l <= 55) {
                        category = 'neon';
                    }
                    
                    colors.push({
                        hsl: hslStr,
                        h: h,
                        s: s,
                        l: l,
                        category: category
                    });
                });
            });
        }
        allPaletteColors = colors;
    }

    function renderColorMosaic(colorsArray) {
        const container = document.getElementById('color-mosaic-container');
        container.innerHTML = '';
        
        const fragment = document.createDocumentFragment();
        colorsArray.forEach(c => {
            const div = document.createElement('div');
            div.className = 'mosaic-cell';
            div.style.background = c.hsl;
            div.title = c.hsl;
            div.onclick = () => {
                setAccent(c.hsl);
                document.getElementById('custom-color-text').value = c.hsl;
                document.getElementById('color-valid-indicator').style.background = c.hsl;
                document.getElementById('color-error-msg').style.display = 'none';
            };
            fragment.appendChild(div);
        });
        container.appendChild(fragment);
    }

    function filterColorCategory(category) {
        // Toggle Category Tab Active States
        document.querySelectorAll('.color-tab').forEach(tab => {
            if (tab.innerText.toLowerCase() === category || (category === 'all' && tab.innerText.toLowerCase() === 'all')) {
                tab.classList.add('active');
            } else {
                tab.classList.remove('active');
            }
        });
        
        if (category === 'all') {
            renderColorMosaic(allPaletteColors);
        } else {
            const filtered = allPaletteColors.filter(c => c.category === category);
            renderColorMosaic(filtered);
        }
    }

    // Rebuild Layout Structure Dynamically based on Template selection
    // Allows extreme aesthetic flexibility (e.g. left dark bar vs top header banner)
    function rebuildLayoutStructure() {
        const dynamicArea = document.getElementById('resume-dynamic-content');
        
        // Extract Current In-Place Text Values
        const nameVal = document.getElementById('field-name') ? document.getElementById('field-name').innerHTML : "{{ $student_detail->full_name }}";
        const titleVal = document.getElementById('field-subtitle') ? document.getElementById('field-subtitle').innerHTML : "Student / Aspiring Professional";
        const emailVal = document.getElementById('field-email') ? document.getElementById('field-email').innerHTML : "{{ $student_detail->email }}";
        const phoneVal = document.getElementById('field-phone') ? document.getElementById('field-phone').innerHTML : "{{ $student_detail->mobile ?? '+88012345678' }}";
        const dobVal = document.getElementById('field-dob') ? document.getElementById('field-dob').innerHTML : "{{ $student_detail->date_of_birth ? dateConvert($student_detail->date_of_birth) : 'N/A' }}";
        const rollVal = document.getElementById('field-roll') ? document.getElementById('field-roll').innerHTML : "{{ $student_detail->roll_no ?? 'N/A' }}";
        
        const summaryVal = document.getElementById('field-summary') ? document.getElementById('field-summary').innerHTML : "";
        const eduTitle = document.getElementById('field-edu-title') ? document.getElementById('field-edu-title').innerHTML : "Academic Student Record - Infix School";
        const eduClass = document.getElementById('field-edu-class') ? document.getElementById('field-edu-class').innerHTML : "{{ $student_detail->defaultClass->class->class_name ?? 'N/A' }}";
        const eduSection = document.getElementById('field-edu-section') ? document.getElementById('field-edu-section').innerHTML : "{{ $student_detail->defaultClass->section->section_name ?? 'N/A' }}";
        const eduAdmission = document.getElementById('field-edu-admission') ? document.getElementById('field-edu-admission').innerHTML : "{{ $student_detail->admission_no }}";
        const eduDesc = document.getElementById('field-edu-desc') ? document.getElementById('field-edu-desc').innerHTML : "";
        
        const skillsVal = document.getElementById('field-skills') ? document.getElementById('field-skills').innerHTML : "";
        const genderVal = document.getElementById('field-gender') ? document.getElementById('field-gender').innerHTML : "{{ $student_detail->gender->base_setup_name ?? 'N/A' }}";
        const bloodVal = document.getElementById('field-blood') ? document.getElementById('field-blood').innerHTML : "{{ $student_detail->bloodGroup->base_setup_name ?? 'N/A' }}";
        const catVal = document.getElementById('field-category') ? document.getElementById('field-category').innerHTML : "{{ $student_detail->category->category_name ?? 'General' }}";
        const addrVal = document.getElementById('field-address') ? document.getElementById('field-address').innerHTML : "{{ $student_detail->current_address ?? '123 School Avenue, Main City' }}";
        const hobbiesVal = document.getElementById('field-hobbies') ? document.getElementById('field-hobbies').innerHTML : "";

        // Extract extra achievement timelines dynamically
        const timelineList = [];
        const timelineNodes = document.querySelectorAll('.r-column-main .resume-section:nth-of-type(3) .timeline-item');
        timelineNodes.forEach(node => {
            const tTitle = node.querySelector('.timeline-item-title') ? node.querySelector('.timeline-item-title').innerHTML : '';
            const tMeta = node.querySelector('.timeline-item-meta') ? node.querySelector('.timeline-item-meta').innerHTML : '';
            const tDesc = node.querySelector('.timeline-item-desc') ? node.querySelector('.timeline-item-desc').innerHTML : '';
            timelineList.push({ title: tTitle, meta: tMeta, desc: tDesc });
        });

        let timelineHTML = '';
        if (timelineList.length > 0) {
            timelineList.forEach(t => {
                timelineHTML += `
                    <div class="timeline-item">
                        <div class="timeline-item-title" contenteditable="true">${t.title}</div>
                        <div class="timeline-item-meta">${t.meta}</div>
                        <div class="timeline-item-desc" contenteditable="true">${t.desc}</div>
                    </div>`;
            });
        } else {
            timelineHTML = `
                <div class="timeline-item">
                    <div class="timeline-item-title" contenteditable="true">School Merit Certificate</div>
                    <div class="timeline-item-meta">May 2026</div>
                    <div class="timeline-item-desc" contenteditable="true">Rewarded for outstanding performance in academic challenges and active class participation.</div>
                </div>`;
        }

        // 1. MODERN & MINIMAL & CLASSIC
        if (currentTemplate === 'modern' || currentTemplate === 'minimal' || currentTemplate === 'classic') {
            dynamicArea.innerHTML = `
                <div class="r-header">
                    <h1 class="r-name" id="field-name" contenteditable="true">${nameVal}</h1>
                    <div class="r-title" id="field-subtitle" contenteditable="true">${titleVal}</div>
                    
                    <div class="r-contact">
                        <div class="r-contact-item">
                            <span class="r-contact-label">Email:</span>
                            <span id="field-email" contenteditable="true">${emailVal}</span>
                        </div>
                        <div class="r-contact-item">
                            <span class="r-contact-label">Phone:</span>
                            <span id="field-phone" contenteditable="true">${phoneVal}</span>
                        </div>
                        <div class="r-contact-item">
                            <span class="r-contact-label">DOB:</span>
                            <span id="field-dob" contenteditable="true">${dobVal}</span>
                        </div>
                        <div class="r-contact-item">
                            <span class="r-contact-label">Roll No:</span>
                            <span id="field-roll" contenteditable="true">${rollVal}</span>
                        </div>
                    </div>
                </div>

                <div class="r-grid">
                    <div class="r-column-main">
                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Professional Summary</span></h2>
                            <div id="field-summary" contenteditable="true">${summaryVal}</div>
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Education Details</span></h2>
                            <div class="timeline-item">
                                <div class="timeline-item-title" id="field-edu-title" contenteditable="true">${eduTitle}</div>
                                <div class="timeline-item-meta">
                                    Class: <span id="field-edu-class" contenteditable="true">${eduClass}</span> |
                                    Section: <span id="field-edu-section" contenteditable="true">${eduSection}</span> |
                                    Admission No: <span id="field-edu-admission" contenteditable="true">${eduAdmission}</span>
                                </div>
                                <div class="timeline-item-desc" id="field-edu-desc" contenteditable="true">${eduDesc}</div>
                            </div>
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Achievements & School Timeline</span></h2>
                            ${timelineHTML}
                        </div>
                    </div>

                    <div class="r-column-side">
                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Skills</span></h2>
                            <div class="skills-badges" id="field-skills" contenteditable="true">${skillsVal}</div>
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Personal Info</span></h2>
                            <div style="font-size: 13px; line-height: 1.8;">
                                <strong>Gender:</strong> <span id="field-gender" contenteditable="true">${genderVal}</span><br>
                                <strong>Blood Group:</strong> <span id="field-blood" contenteditable="true">${bloodVal}</span><br>
                                <strong>Category:</strong> <span id="field-category" contenteditable="true">${catVal}</span><br>
                                <strong>Present Address:</strong> <div id="field-address" contenteditable="true" style="margin-top:4px;">${addrVal}</div>
                            </div>
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Interests</span></h2>
                            <div id="field-hobbies" contenteditable="true" style="font-size: 13px; line-height: 1.8;">${hobbiesVal}</div>
                        </div>
                    </div>
                </div>`;
        }
        
        // 2. TWO-COLUMN COMPACT (Deep Left Column Sidebar Layout)
        else if (currentTemplate === 'compact') {
            dynamicArea.innerHTML = `
                <div class="r-main-container">
                    <div class="r-left-bar">
                        <h1 class="r-name" id="field-name" contenteditable="true" style="font-size: 22px; margin-bottom:5px;">${nameVal}</h1>
                        <div class="r-title" id="field-subtitle" contenteditable="true" style="font-size: 14px; margin-bottom: 25px;">${titleVal}</div>
                        
                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Contact</span></h2>
                            <div class="r-contact">
                                <div><strong>Email:</strong><div id="field-email" contenteditable="true">${emailVal}</div></div>
                                <div><strong>Phone:</strong><div id="field-phone" contenteditable="true">${phoneVal}</div></div>
                                <div><strong>DOB:</strong><div id="field-dob" contenteditable="true">${dobVal}</div></div>
                                <div><strong>Roll No:</strong><div id="field-roll" contenteditable="true">${rollVal}</div></div>
                            </div>
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Skills</span></h2>
                            <div class="skills-badges" id="field-skills" contenteditable="true">${skillsVal}</div>
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Personal Info</span></h2>
                            <div style="font-size: 12px; line-height: 1.8;">
                                <strong>Gender:</strong> <span id="field-gender" contenteditable="true">${genderVal}</span><br>
                                <strong>Blood Group:</strong> <span id="field-blood" contenteditable="true">${bloodVal}</span><br>
                                <strong>Category:</strong> <span id="field-category" contenteditable="true">${catVal}</span><br>
                                <strong>Address:</strong> <div id="field-address" contenteditable="true" style="margin-top:4px;">${addrVal}</div>
                            </div>
                        </div>
                    </div>

                    <div class="r-right-bar">
                        <div class="resume-section">
                            <h2 class="r-section-title" style="margin-top:0;"><span>Professional Summary</span></h2>
                            <div id="field-summary" contenteditable="true">${summaryVal}</div>
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Education Details</span></h2>
                            <div class="timeline-item">
                                <div class="timeline-item-title" id="field-edu-title" contenteditable="true">${eduTitle}</div>
                                <div class="timeline-item-meta">
                                    Class: <span id="field-edu-class" contenteditable="true">${eduClass}</span> |
                                    Section: <span id="field-edu-section" contenteditable="true">${eduSection}</span> |
                                    Admission No: <span id="field-edu-admission" contenteditable="true">${eduAdmission}</span>
                                </div>
                                <div class="timeline-item-desc" id="field-edu-desc" contenteditable="true">${eduDesc}</div>
                            </div>
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Achievements & Timeline</span></h2>
                            ${timelineHTML}
                        </div>

                        <div class="resume-section">
                            <h2 class="r-section-title"><span>Interests & Hobbies</span></h2>
                            <div id="field-hobbies" contenteditable="true" style="font-size: 13px; line-height: 1.8;">${hobbiesVal}</div>
                        </div>
                    </div>
                </div>`;
        }

        // 3. SLEEK BANNER (Full Width Top Accent Colored Banner)
        else if (currentTemplate === 'sleek') {
            dynamicArea.innerHTML = `
                <div class="r-banner">
                    <div>
                        <h1 class="r-name" id="field-name" contenteditable="true" style="font-size: 32px; font-weight:700;">${nameVal}</h1>
                        <div class="r-title" id="field-subtitle" contenteditable="true" style="font-size: 16px;">${titleVal}</div>
                    </div>
                    <div style="text-align: right; font-size:13px; line-height:1.6; opacity:0.9;">
                        <strong>Email:</strong> <span id="field-email" contenteditable="true">${emailVal}</span><br>
                        <strong>Phone:</strong> <span id="field-phone" contenteditable="true">${phoneVal}</span><br>
                        <strong>DOB:</strong> <span id="field-dob" contenteditable="true">${dobVal}</span> | 
                        <strong>Roll No:</strong> <span id="field-roll" contenteditable="true">${rollVal}</span>
                    </div>
                </div>

                <div class="r-content-area">
                    <div class="r-grid">
                        <div class="r-column-main">
                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Professional Summary</span></h2>
                                <div id="field-summary" contenteditable="true">${summaryVal}</div>
                            </div>

                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Education Details</span></h2>
                                <div class="timeline-item">
                                    <div class="timeline-item-title" id="field-edu-title" contenteditable="true">${eduTitle}</div>
                                    <div class="timeline-item-meta">
                                        Class: <span id="field-edu-class" contenteditable="true">${eduClass}</span> |
                                        Section: <span id="field-edu-section" contenteditable="true">${eduSection}</span> |
                                        Admission No: <span id="field-edu-admission" contenteditable="true">${eduAdmission}</span>
                                    </div>
                                    <div class="timeline-item-desc" id="field-edu-desc" contenteditable="true">${eduDesc}</div>
                                </div>
                            </div>

                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Achievements & Timeline</span></h2>
                                ${timelineHTML}
                            </div>
                        </div>

                        <div class="r-column-side">
                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Skills</span></h2>
                                <div class="skills-badges" id="field-skills" contenteditable="true">${skillsVal}</div>
                            </div>

                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Personal Info</span></h2>
                                <div style="font-size: 13px; line-height: 1.8;">
                                    <strong>Gender:</strong> <span id="field-gender" contenteditable="true">${genderVal}</span><br>
                                    <strong>Blood Group:</strong> <span id="field-blood" contenteditable="true">${bloodVal}</span><br>
                                    <strong>Category:</strong> <span id="field-category" contenteditable="true">${catVal}</span><br>
                                    <strong>Address:</strong> <div id="field-address" contenteditable="true" style="margin-top:4px;">${addrVal}</div>
                                </div>
                            </div>

                            <div class="resume-section">
                                <h2 class="r-section-title"><span>Interests</span></h2>
                                <div id="field-hobbies" contenteditable="true" style="font-size: 13px; line-height: 1.8;">${hobbiesVal}</div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        // Preserve Accent Custom Color
        setAccent(currentAccent);
    }
</script>
@endsection
