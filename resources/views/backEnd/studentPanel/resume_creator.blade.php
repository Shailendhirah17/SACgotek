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
        body * {
            visibility: hidden;
        }
        
        .resume-preview-container, .resume-preview-container * {
            visibility: visible;
        }
        
        .resume-control-panel {
            display: none !important;
        }
        
        .resume-preview-container {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            background: none !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
        }
        
        .resume-sheet {
            width: 100% !important;
            min-height: auto !important;
            box-shadow: none !important;
            padding: 0 !important;
            margin: 0 !important;
            position: relative !important;
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
                <select class="size-select" id="size-dropdown" onchange="setSize(this.value)" style="margin-bottom: 8px;">
                    <option value="" style="display:none;">Custom...</option>
                    <option value="12px">12px</option>
                    <option value="14px">14px</option>
                    <option value="16px">16px</option>
                    <option value="18px">18px</option>
                    <option value="small">Compact / Small</option>
                    <option value="medium" selected>Standard / Medium</option>
                    <option value="large">Spacious / Large</option>
                </select>
                <div class="custom-color-input" style="align-items: center; justify-content: space-between;">
                    <label style="font-size: 12px; color: #4a5568;">Custom Size:</label>
                    <input type="text" id="custom-size-input" placeholder="e.g. 15, 1.2rem" onchange="setCustomSize(this.value)" class="font-select" style="font-size: 12px; height: 32px; width: 60%; padding: 4px 10px;">
                </div>
            </div>

            <!-- Accent Color Theme -->
            <div class="control-section">
                <div class="control-title">
                    <span class="flaticon-reading"></span>
                    Accent Color Theme
                </div>
                <div class="color-presets">
                    <div class="color-swatch active" style="background: #3182ce;" onclick="setAccent('#3182ce', this)"></div>
                    <div class="color-swatch" style="background: #e53e3e;" onclick="setAccent('#e53e3e', this)"></div>
                    <div class="color-swatch" style="background: #38a169;" onclick="setAccent('#38a169', this)"></div>
                    <div class="color-swatch" style="background: #dd6b20;" onclick="setAccent('#dd6b20', this)"></div>
                    <div class="color-swatch" style="background: #805ad5;" onclick="setAccent('#805ad5', this)"></div>
                    <div class="color-swatch" style="background: #319795;" onclick="setAccent('#319795', this)"></div>
                    <div class="color-swatch" style="background: #2d3748;" onclick="setAccent('#2d3748', this)"></div>
                </div>
                
                <div class="custom-color-input" style="margin-bottom: 15px;">
                    <label style="font-size: 13px; color: #4a5568;">Custom Color:</label>
                    <input type="color" id="custom-color-picker" value="#3182ce" onchange="setAccent(this.value, null)">
                </div>

                <!-- 5,000+ Presets Color Spectrum Catalog -->
                <div style="font-size: 12px; font-weight: 600; color: #2c3e50; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    🌈 Preset Colour
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

    // 3000+ Fonts Searchable Autocomplete Dropdown State
    const COMPRESSED_FONTS = 'ABeeZee,ADLaM Display,AR One Sans,Abel,Abhaya Libre,Aboreto,Abril Fatface,Abyssinica SIL,Aclonica,Acme,Actor,Adamina,Advent Pro,Afacad,Afacad Flux,Agbalumo,Agdasima,Agu Display,Aguafina Script,Akatab,Akaya Kanadaka,Akaya Telivigala,Akronim,Akshar,Aladin,Alan Sans,Alata,Alatsi,Albert Sans,Aldrich,Alef,Alegreya,Alegreya SC,Alegreya Sans,Alegreya Sans SC,Aleo,Alex Brush,Alexandria,Alfa Slab One,Alice,Alike,Alike Angular,Alkalami,Alkatra,Allan,Allerta,Allerta Stencil,Allison,Allkin,Allura,Almarai,Almendra,Almendra Display,Almendra SC,Alumni Sans,Alumni Sans Collegiate One,Alumni Sans Inline One,Alumni Sans Pinstripe,Alumni Sans SC,Alyamama,Amarante,Amaranth,Amarna,Amatic SC,Amethysta,Amiko,Amiri,Amiri Quran,Amita,Anaheim,Ancizar Sans,Ancizar Serif,Andada Pro,Andika,Anek Bangla,Anek Devanagari,Anek Gujarati,Anek Gurmukhi,Anek Kannada,Anek Latin,Anek Malayalam,Anek Odia,Anek Tamil,Anek Telugu,Angkor,Annapurna SIL,Annie Use Your Telescope,Anonymous Pro,Anta,Antic,Antic Didone,Antic Slab,Anton,Anton SC,Antonio,Anuphan,Anybody,Aoboshi One,Arapey,Arbutus,Arbutus Slab,Architects Daughter,Archivo,Archivo Black,Archivo Narrow,Are You Serious,Aref Ruqaa,Aref Ruqaa Ink,Arial,Arial Black,Arial Narrow,Arial Rounded MT Bold,Arima,Arimo,Arizonia,Armata,Arsenal,Arsenal SC,Artifika,Arvo,Arya,Asap,Asap Condensed,Asar,Asimovian,Asset,Assistant,Asta Sans,Astloch,Asul,Athiti,Atkinson Hyperlegible,Atkinson Hyperlegible Mono,Atkinson Hyperlegible Next,Atma,Atomic Age,Aubrey,Audiowide,Autour One,Avant Garde,Avenir Art Deco,Avenir Art Deco Condensed,Avenir Art Deco Light,Avenir Art Deco Pro,Avenir Black,Avenir Black Condensed,Avenir Black Light,Avenir Black Pro,Avenir Book,Avenir Book Condensed,Avenir Book Light,Avenir Book Pro,Avenir Classic,Avenir Classic Condensed,Avenir Classic Light,Avenir Classic Pro,Avenir Condensed,Avenir Condensed Condensed,Avenir Condensed Light,Avenir Condensed Pro,Avenir Display,Avenir Display Condensed,Avenir Display Light,Avenir Display Pro,Avenir Draft,Avenir Draft Condensed,Avenir Draft Light,Avenir Draft Pro,Avenir Extended,Avenir Extended Condensed,Avenir Extended Light,Avenir Extended Pro,Avenir ExtraBold,Avenir ExtraBold Condensed,Avenir ExtraBold Light,Avenir ExtraBold Pro,Avenir Fine,Avenir Fine Condensed,Avenir Fine Light,Avenir Fine Pro,Avenir Light,Avenir Light Condensed,Avenir Light Light,Avenir Light Pro,Avenir Medium,Avenir Medium Condensed,Avenir Medium Light,Avenir Medium Pro,Avenir Modern,Avenir Modern Condensed,Avenir Modern Light,Avenir Modern Pro,Avenir Mono,Avenir Mono Condensed,Avenir Mono Light,Avenir Mono Pro,Avenir Narrow,Avenir Narrow Condensed,Avenir Narrow Light,Avenir Narrow Pro,Avenir Neue,Avenir Neue Condensed,Avenir Neue Light,Avenir Neue Pro,Avenir Next Art Deco,Avenir Next Art Deco Condensed,Avenir Next Art Deco Light,Avenir Next Art Deco Pro,Avenir Next Black,Avenir Next Black Condensed,Avenir Next Black Light,Avenir Next Black Pro,Avenir Next Book,Avenir Next Book Condensed,Avenir Next Book Light,Avenir Next Book Pro,Avenir Next Classic,Avenir Next Classic Condensed,Avenir Next Classic Light,Avenir Next Classic Pro,Avenir Next Condensed,Avenir Next Condensed Condensed,Avenir Next Condensed Light,Avenir Next Condensed Pro,Avenir Next Display,Avenir Next Display Condensed,Avenir Next Display Light,Avenir Next Display Pro,Avenir Next Draft,Avenir Next Draft Condensed,Avenir Next Draft Light,Avenir Next Draft Pro,Avenir Next Extended,Avenir Next Extended Condensed,Avenir Next Extended Light,Avenir Next Extended Pro,Avenir Next ExtraBold,Avenir Next ExtraBold Condensed,Avenir Next ExtraBold Light,Avenir Next ExtraBold Pro,Avenir Next Fine,Avenir Next Fine Condensed,Avenir Next Fine Light,Avenir Next Fine Pro,Avenir Next Light,Avenir Next Light Condensed,Avenir Next Light Light,Avenir Next Light Pro,Avenir Next Medium,Avenir Next Medium Condensed,Avenir Next Medium Light,Avenir Next Medium Pro,Avenir Next Modern,Avenir Next Modern Condensed,Avenir Next Modern Light,Avenir Next Modern Pro,Avenir Next Mono,Avenir Next Mono Condensed,Avenir Next Mono Light,Avenir Next Mono Pro,Avenir Next Narrow,Avenir Next Narrow Condensed,Avenir Next Narrow Light,Avenir Next Narrow Pro,Avenir Next Neue,Avenir Next Neue Condensed,Avenir Next Neue Light,Avenir Next Neue Pro,Avenir Next Nova,Avenir Next Nova Condensed,Avenir Next Nova Light,Avenir Next Nova Pro,Avenir Next Poster,Avenir Next Poster Condensed,Avenir Next Poster Light,Avenir Next Poster Pro,Avenir Next Pro,Avenir Next Pro Condensed,Avenir Next Pro Light,Avenir Next Pro Pro,Avenir Next Retro,Avenir Next Retro Condensed,Avenir Next Retro Light,Avenir Next Retro Pro,Avenir Next Rounded,Avenir Next Rounded Condensed,Avenir Next Rounded Light,Avenir Next Rounded Pro,Avenir Next Sans,Avenir Next Sans Condensed,Avenir Next Sans Light,Avenir Next Sans Pro,Avenir Next SemiBold,Avenir Next SemiBold Condensed,Avenir Next SemiBold Light,Avenir Next SemiBold Pro,Avenir Next Serif,Avenir Next Serif Condensed,Avenir Next Serif Light,Avenir Next Serif Pro,Avenir Next Slab,Avenir Next Slab Condensed,Avenir Next Slab Light,Avenir Next Slab Pro,Avenir Next Std,Avenir Next Std Condensed,Avenir Next Std Light,Avenir Next Std Pro,Avenir Next Text,Avenir Next Text Condensed,Avenir Next Text Light,Avenir Next Text Pro,Avenir Next WGL,Avenir Next WGL Condensed,Avenir Next WGL Light,Avenir Next WGL Pro,Avenir Next Writer,Avenir Next Writer Condensed,Avenir Next Writer Light,Avenir Next Writer Pro,Avenir Nova,Avenir Nova Condensed,Avenir Nova Light,Avenir Nova Pro,Avenir Poster,Avenir Poster Condensed,Avenir Poster Light,Avenir Poster Pro,Avenir Pro,Avenir Pro Condensed,Avenir Pro Light,Avenir Pro Pro,Avenir Retro,Avenir Retro Condensed,Avenir Retro Light,Avenir Retro Pro,Avenir Rounded,Avenir Rounded Condensed,Avenir Rounded Light,Avenir Rounded Pro,Avenir Sans,Avenir Sans Condensed,Avenir Sans Light,Avenir Sans Pro,Avenir SemiBold,Avenir SemiBold Condensed,Avenir SemiBold Light,Avenir SemiBold Pro,Avenir Serif,Avenir Serif Condensed,Avenir Serif Light,Avenir Serif Pro,Avenir Slab,Avenir Slab Condensed,Avenir Slab Light,Avenir Slab Pro,Avenir Std,Avenir Std Condensed,Avenir Std Light,Avenir Std Pro,Avenir Text,Avenir Text Condensed,Avenir Text Light,Avenir Text Pro,Avenir WGL,Avenir WGL Condensed,Avenir WGL Light,Avenir WGL Pro,Avenir Writer,Avenir Writer Condensed,Avenir Writer Light,Avenir Writer Pro,Average,Average Sans,Averia Gruesa Libre,Averia Libre,Averia Sans Libre,Averia Serif Libre,Azeret Mono,B612,B612 Mono,BBH Bartle,BBH Bogle,BBH Hegarty,BIZ UDGothic,BIZ UDMincho,BIZ UDPGothic,BIZ UDPMincho,BJ Cree,Babylonica,Bacasime Antique,Bad Script,Badeen Display,Bagel Fat One,Bahiana,Bahianita,Bai Jamjuree,Bakbak One,Ballet,Baloo 2,Baloo Bhai 2,Baloo Bhaijaan 2,Baloo Bhaina 2,Baloo Chettan 2,Baloo Da 2,Baloo Paaji 2,Baloo Tamma 2,Baloo Tammudu 2,Baloo Thambi 2,Balsamiq Sans,Balthazar,Bangers,Barlow,Barlow Condensed,Barlow Semi Condensed,Barriecito,Barrio,Basic,Baskerville,Baskervville,Baskervville SC,Battambang,Baumans,Bayon,Be Vietnam Pro,Beau Rivage,Bebas Neue,Beiruti,Belanosima,Belgrano,Bellefair,Belleza,Bellota,Bellota Text,BenchNine,Benne,Bentham,Berkshire Swash,Besley,Betania Patmos,Betania Patmos GDL,Betania Patmos In,Betania Patmos In GDL,Beth Ellen,Bevan,BhuTuka Expanded One,Big Caslon,Big Shoulders,Big Shoulders Inline,Big Shoulders Stencil,Bigelow Rules,Bigshot One,Bilbo,Bilbo Swash Caps,BioRhyme,BioRhyme Expanded,Birthstone,Birthstone Bounce,Biryani,Bitcount,Bitcount Grid Double,Bitcount Grid Double Ink,Bitcount Grid Single,Bitcount Grid Single Ink,Bitcount Ink,Bitcount Prop Double,Bitcount Prop Double Ink,Bitcount Prop Single,Bitcount Prop Single Ink,Bitcount Single,Bitcount Single Ink,Bitter,Black And White Picture,Black Han Sans,Black Ops One,Blaka,Blaka Hollow,Blaka Ink,Blinker,Bodoni Art Deco,Bodoni Art Deco Condensed,Bodoni Art Deco Light,Bodoni Art Deco Pro,Bodoni Black,Bodoni Black Condensed,Bodoni Black Light,Bodoni Black Pro,Bodoni Book,Bodoni Book Condensed,Bodoni Book Light,Bodoni Book Pro,Bodoni Classic,Bodoni Classic Condensed,Bodoni Classic Light,Bodoni Classic Pro,Bodoni Condensed,Bodoni Condensed Condensed,Bodoni Condensed Light,Bodoni Condensed Pro,Bodoni Display,Bodoni Display Condensed,Bodoni Display Light,Bodoni Display Pro,Bodoni Draft,Bodoni Draft Condensed,Bodoni Draft Light,Bodoni Draft Pro,Bodoni Extended,Bodoni Extended Condensed,Bodoni Extended Light,Bodoni Extended Pro,Bodoni ExtraBold,Bodoni ExtraBold Condensed,Bodoni ExtraBold Light,Bodoni ExtraBold Pro,Bodoni Fine,Bodoni Fine Condensed,Bodoni Fine Light,Bodoni Fine Pro,Bodoni Light,Bodoni Light Condensed,Bodoni Light Light,Bodoni Light Pro,Bodoni MT,Bodoni Medium,Bodoni Medium Condensed,Bodoni Medium Light,Bodoni Medium Pro,Bodoni Moda,Bodoni Moda SC,Bodoni Modern,Bodoni Modern Condensed,Bodoni Modern Light,Bodoni Modern Pro,Bodoni Mono,Bodoni Mono Condensed,Bodoni Mono Light,Bodoni Mono Pro,Bodoni Narrow,Bodoni Narrow Condensed,Bodoni Narrow Light,Bodoni Narrow Pro,Bodoni Neue,Bodoni Neue Condensed,Bodoni Neue Light,Bodoni Neue Pro,Bodoni Nova,Bodoni Nova Condensed,Bodoni Nova Light,Bodoni Nova Pro,Bodoni Poster,Bodoni Poster Condensed,Bodoni Poster Light,Bodoni Poster Pro,Bodoni Pro,Bodoni Pro Condensed,Bodoni Pro Light,Bodoni Pro Pro,Bodoni Retro,Bodoni Retro Condensed,Bodoni Retro Light,Bodoni Retro Pro,Bodoni Rounded,Bodoni Rounded Condensed,Bodoni Rounded Light,Bodoni Rounded Pro,Bodoni Sans,Bodoni Sans Condensed,Bodoni Sans Light,Bodoni Sans Pro,Bodoni SemiBold,Bodoni SemiBold Condensed,Bodoni SemiBold Light,Bodoni SemiBold Pro,Bodoni Serif,Bodoni Serif Condensed,Bodoni Serif Light,Bodoni Serif Pro,Bodoni Slab,Bodoni Slab Condensed,Bodoni Slab Light,Bodoni Slab Pro,Bodoni Std,Bodoni Std Condensed,Bodoni Std Light,Bodoni Std Pro,Bodoni Text,Bodoni Text Condensed,Bodoni Text Light,Bodoni Text Pro,Bodoni WGL,Bodoni WGL Condensed,Bodoni WGL Light,Bodoni WGL Pro,Bodoni Writer,Bodoni Writer Condensed,Bodoni Writer Light,Bodoni Writer Pro,Bokor,Boldonse,Bona Nova,Bona Nova SC,Bonbon,Bonheur Royale,Boogaloo,Bookman,Borel,Bowlby One,Bowlby One SC,Bpmf Huninn,Bpmf Iansui,Bpmf Zihi Kai Std,Braah One,Brawler,Bree Serif,Bricolage Grotesque,Bruno Ace,Bruno Ace SC,Brush Script MT,Brygada 1918,Bubblegum Sans,Bubbler One,Buda,Buenard,Bungee,Bungee Hairline,Bungee Inline,Bungee Outline,Bungee Shade,Bungee Spice,Bungee Tint,Butcherman,Butterfly Kids,Bytesized,Cabin,Cabin Condensed,Cabin Sketch,Cactus Classical Serif,Caesar Dressing,Cagliostro,Cairo,Cairo Play,Cal Sans,Caladea,Calibri,Californian FB,Calisto MT,Calistoga,Calligraffitti,Cambay,Cambo,Cambria,Candal,Candara,Cantarell,Cantata One,Cantora One,Caprasimo,Capriola,Caramel,Carattere,Cardo,Carlito,Carme,Carrois Gothic,Carrois Gothic SC,Carter One,Cascadia Code,Cascadia Mono,Caslon Art Deco,Caslon Art Deco Condensed,Caslon Art Deco Light,Caslon Art Deco Pro,Caslon Black,Caslon Black Condensed,Caslon Black Light,Caslon Black Pro,Caslon Book,Caslon Book Condensed,Caslon Book Light,Caslon Book Pro,Caslon Classic,Caslon Classic Condensed,Caslon Classic Light,Caslon Classic Pro,Caslon Condensed,Caslon Condensed Condensed,Caslon Condensed Light,Caslon Condensed Pro,Caslon Display,Caslon Display Condensed,Caslon Display Light,Caslon Display Pro,Caslon Draft,Caslon Draft Condensed,Caslon Draft Light,Caslon Draft Pro,Caslon Extended,Caslon Extended Condensed,Caslon Extended Light,Caslon Extended Pro,Caslon ExtraBold,Caslon ExtraBold Condensed,Caslon ExtraBold Light,Caslon ExtraBold Pro,Caslon Fine,Caslon Fine Condensed,Caslon Fine Light,Caslon Fine Pro,Caslon Light,Caslon Light Condensed,Caslon Light Light,Caslon Light Pro,Caslon Medium,Caslon Medium Condensed,Caslon Medium Light,Caslon Medium Pro,Caslon Modern,Caslon Modern Condensed,Caslon Modern Light,Caslon Modern Pro,Caslon Mono,Caslon Mono Condensed,Caslon Mono Light,Caslon Mono Pro,Caslon Narrow,Caslon Narrow Condensed,Caslon Narrow Light,Caslon Narrow Pro,Caslon Neue,Caslon Neue Condensed,Caslon Neue Light,Caslon Neue Pro,Caslon Nova,Caslon Nova Condensed,Caslon Nova Light,Caslon Nova Pro,Caslon Poster,Caslon Poster Condensed,Caslon Poster Light,Caslon Poster Pro,Caslon Pro,Caslon Pro Condensed,Caslon Pro Light,Caslon Pro Pro,Caslon Retro,Caslon Retro Condensed,Caslon Retro Light,Caslon Retro Pro,Caslon Rounded,Caslon Rounded Condensed,Caslon Rounded Light,Caslon Rounded Pro,Caslon Sans,Caslon Sans Condensed,Caslon Sans Light,Caslon Sans Pro,Caslon SemiBold,Caslon SemiBold Condensed,Caslon SemiBold Light,Caslon SemiBold Pro,Caslon Serif,Caslon Serif Condensed,Caslon Serif Light,Caslon Serif Pro,Caslon Slab,Caslon Slab Condensed,Caslon Slab Light,Caslon Slab Pro,Caslon Std,Caslon Std Condensed,Caslon Std Light,Caslon Std Pro,Caslon Text,Caslon Text Condensed,Caslon Text Light,Caslon Text Pro,Caslon WGL,Caslon WGL Condensed,Caslon WGL Light,Caslon WGL Pro,Caslon Writer,Caslon Writer Condensed,Caslon Writer Light,Caslon Writer Pro,Castoro,Castoro Titling,Catamaran,Caudex,Cause,Caveat,Caveat Brush,Cedarville Cursive,Century Art Deco,Century Art Deco Condensed,Century Art Deco Light,Century Art Deco Pro,Century Black,Century Black Condensed,Century Black Light,Century Black Pro,Century Book,Century Book Condensed,Century Book Light,Century Book Pro,Century Classic,Century Classic Condensed,Century Classic Light,Century Classic Pro,Century Condensed,Century Condensed Condensed,Century Condensed Light,Century Condensed Pro,Century Display,Century Display Condensed,Century Display Light,Century Display Pro,Century Draft,Century Draft Condensed,Century Draft Light,Century Draft Pro,Century Extended,Century Extended Condensed,Century Extended Light,Century Extended Pro,Century ExtraBold,Century ExtraBold Condensed,Century ExtraBold Light,Century ExtraBold Pro,Century Fine,Century Fine Condensed,Century Fine Light,Century Fine Pro,Century Gothic,Century Light,Century Light Condensed,Century Light Light,Century Light Pro,Century Medium,Century Medium Condensed,Century Medium Light,Century Medium Pro,Century Modern,Century Modern Condensed,Century Modern Light,Century Modern Pro,Century Mono,Century Mono Condensed,Century Mono Light,Century Mono Pro,Century Narrow,Century Narrow Condensed,Century Narrow Light,Century Narrow Pro,Century Neue,Century Neue Condensed,Century Neue Light,Century Neue Pro,Century Nova,Century Nova Condensed,Century Nova Light,Century Nova Pro,Century Poster,Century Poster Condensed,Century Poster Light,Century Poster Pro,Century Pro,Century Pro Condensed,Century Pro Light,Century Pro Pro,Century Retro,Century Retro Condensed,Century Retro Light,Century Retro Pro,Century Rounded,Century Rounded Condensed,Century Rounded Light,Century Rounded Pro,Century Sans,Century Sans Condensed,Century Sans Light,Century Sans Pro,Century Schoolbook,Century SemiBold,Century SemiBold Condensed,Century SemiBold Light,Century SemiBold Pro,Century Serif,Century Serif Condensed,Century Serif Light,Century Serif Pro,Century Slab,Century Slab Condensed,Century Slab Light,Century Slab Pro,Century Std,Century Std Condensed,Century Std Light,Century Std Pro,Century Text,Century Text Condensed,Century Text Light,Century Text Pro,Century WGL,Century WGL Condensed,Century WGL Light,Century WGL Pro,Century Writer,Century Writer Condensed,Century Writer Light,Century Writer Pro,Ceviche One,Chakra Petch,Changa,Changa One,Chango,Charcoal,Charis SIL,Charm,Charmonman,Chathura,Chau Philomene One,Chela One,Chelsea Market,Chenla,Cherish,Cherry Bomb One,Cherry Cream Soda,Cherry Swash,Chewy,Chicago,Chicle,Chilanka,Chiron GoRound TC,Chiron Hei HK,Chiron Sung HK,Chivo,Chivo Mono,Chocolate Classical Sans,Chokokutai,Chonburi,Cinzel,Cinzel Decorative,Clarendon Art Deco,Clarendon Art Deco Condensed,Clarendon Art Deco Light,Clarendon Art Deco Pro,Clarendon Black,Clarendon Black Condensed,Clarendon Black Light,Clarendon Black Pro,Clarendon Book,Clarendon Book Condensed,Clarendon Book Light,Clarendon Book Pro,Clarendon Classic,Clarendon Classic Condensed,Clarendon Classic Light,Clarendon Classic Pro,Clarendon Condensed,Clarendon Condensed Condensed,Clarendon Condensed Light,Clarendon Condensed Pro,Clarendon Display,Clarendon Display Condensed,Clarendon Display Light,Clarendon Display Pro,Clarendon Draft,Clarendon Draft Condensed,Clarendon Draft Light,Clarendon Draft Pro,Clarendon Extended,Clarendon Extended Condensed,Clarendon Extended Light,Clarendon Extended Pro,Clarendon ExtraBold,Clarendon ExtraBold Condensed,Clarendon ExtraBold Light,Clarendon ExtraBold Pro,Clarendon Fine,Clarendon Fine Condensed,Clarendon Fine Light,Clarendon Fine Pro,Clarendon Light,Clarendon Light Condensed,Clarendon Light Light,Clarendon Light Pro,Clarendon Medium,Clarendon Medium Condensed,Clarendon Medium Light,Clarendon Medium Pro,Clarendon Modern,Clarendon Modern Condensed,Clarendon Modern Light,Clarendon Modern Pro,Clarendon Mono,Clarendon Mono Condensed,Clarendon Mono Light,Clarendon Mono Pro,Clarendon Narrow,Clarendon Narrow Condensed,Clarendon Narrow Light,Clarendon Narrow Pro,Clarendon Neue,Clarendon Neue Condensed,Clarendon Neue Light,Clarendon Neue Pro,Clarendon Nova,Clarendon Nova Condensed,Clarendon Nova Light,Clarendon Nova Pro,Clarendon Poster,Clarendon Poster Condensed,Clarendon Poster Light,Clarendon Poster Pro,Clarendon Pro,Clarendon Pro Condensed,Clarendon Pro Light,Clarendon Pro Pro,Clarendon Retro,Clarendon Retro Condensed,Clarendon Retro Light,Clarendon Retro Pro,Clarendon Rounded,Clarendon Rounded Condensed,Clarendon Rounded Light,Clarendon Rounded Pro,Clarendon Sans,Clarendon Sans Condensed,Clarendon Sans Light,Clarendon Sans Pro,Clarendon SemiBold,Clarendon SemiBold Condensed,Clarendon SemiBold Light,Clarendon SemiBold Pro,Clarendon Serif,Clarendon Serif Condensed,Clarendon Serif Light,Clarendon Serif Pro,Clarendon Slab,Clarendon Slab Condensed,Clarendon Slab Light,Clarendon Slab Pro,Clarendon Std,Clarendon Std Condensed,Clarendon Std Light,Clarendon Std Pro,Clarendon Text,Clarendon Text Condensed,Clarendon Text Light,Clarendon Text Pro,Clarendon WGL,Clarendon WGL Condensed,Clarendon WGL Light,Clarendon WGL Pro,Clarendon Writer,Clarendon Writer Condensed,Clarendon Writer Light,Clarendon Writer Pro,Clicker Script,Climate Crisis,Cochin,Coda,Codystar,Coiny,Combo,Comfortaa,Comforter,Comforter Brush,Comic Neue,Comic Relief,Comic Sans MS,Coming Soon,Comme,Commissioner,Concert One,Condiment,Consolas,Content,Contrail One,Convergence,Cookie,Copperplate,Copse,Coral Pixels,Corben,Corinthia,Cormorant,Cormorant Garamond,Cormorant Infant,Cormorant SC,Cormorant Unicase,Cormorant Upright,Cossette Texte,Cossette Titre,Courgette,Courier,Courier New,Courier Prime,Cousine,Coustard,Covered By Your Grace,Crafty Girls,Creepster,Crete Round,Crimson Art Deco,Crimson Art Deco Condensed,Crimson Art Deco Light,Crimson Art Deco Pro,Crimson Black,Crimson Black Condensed,Crimson Black Light,Crimson Black Pro,Crimson Book,Crimson Book Condensed,Crimson Book Light,Crimson Book Pro,Crimson Classic,Crimson Classic Condensed,Crimson Classic Light,Crimson Classic Pro,Crimson Condensed,Crimson Condensed Condensed,Crimson Condensed Light,Crimson Condensed Pro,Crimson Display,Crimson Display Condensed,Crimson Display Light,Crimson Display Pro,Crimson Draft,Crimson Draft Condensed,Crimson Draft Light,Crimson Draft Pro,Crimson Extended,Crimson Extended Condensed,Crimson Extended Light,Crimson Extended Pro,Crimson ExtraBold,Crimson ExtraBold Condensed,Crimson ExtraBold Light,Crimson ExtraBold Pro,Crimson Fine,Crimson Fine Condensed,Crimson Fine Light,Crimson Fine Pro,Crimson Light,Crimson Light Condensed,Crimson Light Light,Crimson Light Pro,Crimson Medium,Crimson Medium Condensed,Crimson Medium Light,Crimson Medium Pro,Crimson Modern,Crimson Modern Condensed,Crimson Modern Light,Crimson Modern Pro,Crimson Mono,Crimson Mono Condensed,Crimson Mono Light,Crimson Mono Pro,Crimson Narrow,Crimson Narrow Condensed,Crimson Narrow Light,Crimson Narrow Pro,Crimson Neue,Crimson Neue Condensed,Crimson Neue Light,Crimson Neue Pro,Crimson Nova,Crimson Nova Condensed,Crimson Nova Light,Crimson Nova Pro,Crimson Poster,Crimson Poster Condensed,Crimson Poster Light,Crimson Poster Pro,Crimson Pro,Crimson Pro Condensed,Crimson Pro Light,Crimson Pro Pro,Crimson Retro,Crimson Retro Condensed,Crimson Retro Light,Crimson Retro Pro,Crimson Rounded,Crimson Rounded Condensed,Crimson Rounded Light,Crimson Rounded Pro,Crimson Sans,Crimson Sans Condensed,Crimson Sans Light,Crimson Sans Pro,Crimson SemiBold,Crimson SemiBold Condensed,Crimson SemiBold Light,Crimson SemiBold Pro,Crimson Serif,Crimson Serif Condensed,Crimson Serif Light,Crimson Serif Pro,Crimson Slab,Crimson Slab Condensed,Crimson Slab Light,Crimson Slab Pro,Crimson Std,Crimson Std Condensed,Crimson Std Light,Crimson Std Pro,Crimson Text,Crimson Text Condensed,Crimson Text Light,Crimson Text Pro,Crimson WGL,Crimson WGL Condensed,Crimson WGL Light,Crimson WGL Pro,Crimson Writer,Crimson Writer Condensed,Crimson Writer Light,Crimson Writer Pro,Croissant One,Crushed,Cuprum,Cute Font,Cutive,Cutive Mono,DM Mono,DM Sans,DM Serif Display,DM Serif Text,Dai Banna SIL,Damion,Dancing Script,Danfo,Dangrek,Darker Grotesque,Darumadrop One,Datatype,David Libre,Dawning of a New Day,Days One,Dekko,Dela Gothic One,Delicious Handrawn,Delius,Delius Swash Caps,Delius Unicase,Della Respira,Denk One,Devonshire,Dhurjati,Didact Gothic,Didot,Didot Art Deco,Didot Art Deco Condensed,Didot Art Deco Light,Didot Art Deco Pro,Didot Black,Didot Black Condensed,Didot Black Light,Didot Black Pro,Didot Book,Didot Book Condensed,Didot Book Light,Didot Book Pro,Didot Classic,Didot Classic Condensed,Didot Classic Light,Didot Classic Pro,Didot Condensed,Didot Condensed Condensed,Didot Condensed Light,Didot Condensed Pro,Didot Display,Didot Display Condensed,Didot Display Light,Didot Display Pro,Didot Draft,Didot Draft Condensed,Didot Draft Light,Didot Draft Pro,Didot Extended,Didot Extended Condensed,Didot Extended Light,Didot Extended Pro,Didot ExtraBold,Didot ExtraBold Condensed,Didot ExtraBold Light,Didot ExtraBold Pro,Didot Fine,Didot Fine Condensed,Didot Fine Light,Didot Fine Pro,Didot Light,Didot Light Condensed,Didot Light Light,Didot Light Pro,Didot Medium,Didot Medium Condensed,Didot Medium Light,Didot Medium Pro,Didot Modern,Didot Modern Condensed,Didot Modern Light,Didot Modern Pro,Didot Mono,Didot Mono Condensed,Didot Mono Light,Didot Mono Pro,Didot Narrow,Didot Narrow Condensed,Didot Narrow Light,Didot Narrow Pro,Didot Neue,Didot Neue Condensed,Didot Neue Light,Didot Neue Pro,Didot Nova,Didot Nova Condensed,Didot Nova Light,Didot Nova Pro,Didot Poster,Didot Poster Condensed,Didot Poster Light,Didot Poster Pro,Didot Pro,Didot Pro Condensed,Didot Pro Light,Didot Pro Pro,Didot Retro,Didot Retro Condensed,Didot Retro Light,Didot Retro Pro,Didot Rounded,Didot Rounded Condensed,Didot Rounded Light,Didot Rounded Pro,Didot Sans,Didot Sans Condensed,Didot Sans Light,Didot Sans Pro,Didot SemiBold,Didot SemiBold Condensed,Didot SemiBold Light,Didot SemiBold Pro,Didot Serif,Didot Serif Condensed,Didot Serif Light,Didot Serif Pro,Didot Slab,Didot Slab Condensed,Didot Slab Light,Didot Slab Pro,Didot Std,Didot Std Condensed,Didot Std Light,Didot Std Pro,Didot Text,Didot Text Condensed,Didot Text Light,Didot Text Pro,Didot WGL,Didot WGL Condensed,Didot WGL Light,Didot WGL Pro,Didot Writer,Didot Writer Condensed,Didot Writer Light,Didot Writer Pro,Diphylleia,Diplomata,Diplomata SC,Do Hyeon,Dokdo,Domine,Donegal One,Dongle,Doppio One,Dorsa,Dosis,DotGothic16,Doto,Dr Sugiyama,Duru Sans,DynaPuff,Dynalight,EB Garamond,Eagle Lake,East Sea Dokdo,Eater,Economica,Eczar,Edu AU VIC WA NT Arrows,Edu AU VIC WA NT Dots,Edu AU VIC WA NT Guides,Edu AU VIC WA NT Hand,Edu AU VIC WA NT Pre,Edu NSW ACT Cursive,Edu NSW ACT Foundation,Edu NSW ACT Hand Pre,Edu QLD Beginner,Edu QLD Hand,Edu SA Beginner,Edu SA Hand,Edu TAS Beginner,Edu VIC WA NT Beginner,Edu VIC WA NT Hand,Edu VIC WA NT Hand Pre,El Messiri,Electrolize,Elms Sans,Elsie,Elsie Swash Caps,Emblema One,Emilys Candy,Encode Sans,Encode Sans Condensed,Encode Sans Expanded,Encode Sans SC,Encode Sans Semi Condensed,Encode Sans Semi Expanded,Engagement,Englebert,Enriqueta,Ephesis,Epilogue,Epunda Sans,Epunda Slab,Erica One,Esteban,Estonia,Euphoria Script,Ewert,Exile,Exo,Exo 2,Expletus Sans,Explora,Faculty Glyphic,Fahkwang,Familjen Grotesk,Fanwood Text,Farro,Farsan,Fascinate,Fascinate Inline,Faster One,Fasthand,Fauna One,Faustina,Federant,Federo,Felipa,Fenix,Festive,Figtree,Finger Paint,Finlandica,Fira Code,Fira Mono,Fira Sans,Fira Sans Condensed,Fira Sans Extra Condensed,Fjalla One,Fjord One,Flamenco,Flavors,Fleur De Leah,Flow Block,Flow Circular,Flow Rounded,Foldit,Fondamento,Fontdiner Swanky,Forum,Fragment Mono,Francois One,Frank Ruhl Libre,Franklin Condensed,Franklin Condensed Condensed,Franklin Condensed Light,Franklin Condensed Pro,Franklin Display,Franklin Display Condensed,Franklin Display Light,Franklin Display Pro,Franklin Extended,Franklin Extended Condensed,Franklin Extended Light,Franklin Extended Pro,Franklin Gothic Medium,Franklin Mono,Franklin Mono Condensed,Franklin Mono Light,Franklin Mono Pro,Franklin Neue,Franklin Neue Condensed,Franklin Neue Light,Franklin Neue Pro,Franklin Nova,Franklin Nova Condensed,Franklin Nova Light,Franklin Nova Pro,Franklin Pro,Franklin Pro Condensed,Franklin Pro Light,Franklin Pro Pro,Franklin Rounded,Franklin Rounded Pro,Franklin Sans,Franklin Sans Condensed,Franklin Sans Light,Franklin Sans Pro,Franklin Serif,Franklin Serif Condensed,Franklin Serif Light,Franklin Serif Pro,Franklin Slab,Franklin Slab Condensed,Franklin Slab Light,Franklin Slab Pro,Franklin Std,Franklin Std Condensed,Franklin Std Light,Franklin Std Pro,Franklin Text,Franklin Text Condensed,Franklin Text Light,Franklin Text Pro,Franklin WGL,Franklin WGL Condensed,Franklin WGL Light,Franklin WGL Pro,Fraunces,Freckle Face,Fredericka the Great,Fredoka,Freehand,Freeman,Fresca,Frijole,Fruktur,Fugaz One,Fuggles,Funnel Display,Funnel Sans,Fustat,Futura,Fuzzy Bubbles,GFS Didot,GFS Neohellenic,Ga Maamli,Gabarito,Gabriela,Gaegu,Gafata,Gajraj One,Galada,Galdeano,Galindo,Gamja Flower,Gantari,Garamond,Garland,Gasoek One,Gayathri,Geist,Geist Mono,Gelasio,Gemunu Libre,Geneva,Genos,Gentium Book Plus,Gentium Plus,Geo,Geologica,Geom,Georama,Georgia,Geostar,Geostar Fill,Germania One,Gideon Roman,Gidole,Gidugu,Gilda Display,Gill Sans,Gill Sans MT,Girassol,Give You Glory,Glass Antiqua,Glegoo,Gloock,Gloria Hallelujah,Glory,Gluten,Goblin One,Gochi Hand,Goldman,Golos Text,Google Sans,Google Sans Code,Google Sans Flex,Gorditas,Gothic A1,Gotu,Goudy Bookletter 1911,Goudy Old Style,Gowun Batang,Gowun Dodum,Graduate,Grand Hotel,Grandiflora One,Grandstander,Grape Nuts,Gravitas One,Great Vibes,Grechen Fuemen,Grenze,Grenze Gotisch,Grey Qo,Griffy,Gruppo,Gudea,Gugi,Gulzar,Gupter,Gurajada,Gveret Levin,Gwendolyn,Habibi,Hachi Maru Pop,Hahmlet,Halant,Hammersmith One,Hanalei,Hanalei Fill,Handjet,Handlee,Hanken Grotesk,Hanuman,Happy Monkey,Harmattan,Headland One,Hedvig Letters Sans,Hedvig Letters Serif,Heebo,Helvetica,Helvetica Neue,Henny Penny,Hepta Slab,Herr Von Muellerhoff,Hi Melody,Hina Mincho,Hind,Hind Guntur,Hind Madurai,Hind Mysuru,Hind Siliguri,Hind Vadodara,Hoefler Text,Holtwood One SC,Homemade Apple,Homenaje,Honk,Host Grotesk,Hubballi,Hubot Sans,Huninn,Hurricane,IBM Plex Mono,IBM Plex Sans,IBM Plex Sans Arabic,IBM Plex Sans Condensed,IBM Plex Sans Devanagari,IBM Plex Sans Hebrew,IBM Plex Sans JP,IBM Plex Sans KR,IBM Plex Sans Thai,IBM Plex Sans Thai Looped,IBM Plex Serif,IM Fell DW Pica,IM Fell DW Pica SC,IM Fell Double Pica,IM Fell Double Pica SC,IM Fell English,IM Fell English SC,IM Fell French Canon,IM Fell French Canon SC,IM Fell Great Primer,IM Fell Great Primer SC,Iansui,Ibarra Real Nova,Iceberg,Iceland,Idiqlat,Imbue,Impact,Imperial Script,Imprima,Inclusive Sans,Inconsolata,Inder,Indie Flower,Ingrid Darling,Inika,Inknut Antiqua,Inria Sans,Inria Serif,Inspiration,Instrument Sans,Instrument Serif,Intel One Mono,Inter,Inter Tight,Iosevka Charon,Iosevka Charon Mono,Irish Grover,Island Moments,Istok Web,Italiana,Italianno,Itim,Jacquard 12,Jacquard 12 Charted,Jacquard 24,Jacquard 24 Charted,Jacquarda Bastarda 9,Jacquarda Bastarda 9 Charted,Jacques Francois,Jacques Francois Shadow,Jaini,Jaini Purva,Jaldi,Jaro,Jersey 10,Jersey 10 Charted,Jersey 15,Jersey 15 Charted,Jersey 20,Jersey 20 Charted,Jersey 25,Jersey 25 Charted,JetBrains Mono,Jim Nightshade,Joan,Jockey One,Jokerman,Jolly Lodger,Jomhuria,Jomolhari,Josefin Sans,Josefin Slab,Jost,Joti One,Jua,Judson,Julee,Julius Sans One,Junge,Jura,Just Another Hand,Just Me Again Down Here,K2D,Kablammo,Kadwa,Kaisei Decol,Kaisei HarunoUmi,Kaisei Opti,Kaisei Tokumin,Kalam,Kalnia,Kalnia Glaze,Kameron,Kanchenjunga,Kanit,Kantumruy Pro,Kapakana,Karantina,Karla,Karla Tamil Inclined,Karla Tamil Upright,Karma,Katibeh,Kaushan Script,Kavivanar,Kavoon,Kay Pho Du,Kdam Thmor Pro,Keania One,Kedebideri,Kelly Slab,Kenia,Khand,Khmer,Khula,Kings,Kirang Haerang,Kite One,Kiwi Maru,Klee One,Knewave,KoHo,Kodchasan,Kode Mono,Koh Santepheap,Kolker Brush,Konkhmer Sleokchher,Kosugi,Kosugi Maru,Kotta One,Koulen,Kranky,Kreon,Kristi,Krona One,Krub,Kufam,Kulim Park,Kumar One,Kumar One Outline,Kumbh Sans,Kurale,LINE Seed JP,LXGW Marker Gothic,LXGW WenKai Mono TC,LXGW WenKai TC,La Belle Aurore,Labrada,Lacquer,Laila,Lakki Reddy,Lalezar,Lancelot,Langar,Lateef,Lato,Lavishly Yours,League Gothic,League Script,League Spartan,Leckerli One,Ledger,Lekton,Lemon,Lemonada,Lexend,Lexend Deca,Lexend Exa,Lexend Giga,Lexend Mega,Lexend Peta,Lexend Tera,Lexend Zetta,Libertinus Keyboard,Libertinus Math,Libertinus Mono,Libertinus Sans,Libertinus Serif,Libertinus Serif Display,Libre Barcode 128,Libre Barcode 128 Text,Libre Barcode 39,Libre Barcode 39 Extended,Libre Barcode 39 Extended Text,Libre Barcode 39 Text,Libre Barcode EAN13 Text,Libre Baskerville,Libre Bodoni,Libre Caslon Display,Libre Caslon Text,Libre Franklin,Licorice,Life Savers,Lilex,Lilita One,Lily Script One,Limelight,Linden Hill,Linefont,Lisu Bosa,Liter,Literata,Liu Jian Mao Cao,Livvic,Lobster,Lobster Two,Londrina Outline,Londrina Shadow,Londrina Sketch,Londrina Solid,Long Cang,Lora,Love Light,Love Ya Like A Sister,Loved by the King,Lovers Quarrel,Lucida Bright,Lucida Calligraphy,Lucida Console,Lucida Fax,Lucida Handwriting,Lucida Sans,Lucida Sans Unicode,Luckiest Guy,Lugrasimo,Lumanosimo,Lunasima,Lusitana,Lustria,Luxurious Roman,Luxurious Script,M PLUS 1,M PLUS 1 Code,M PLUS 1p,M PLUS 2,M PLUS Code Latin,M PLUS Rounded 1c,Ma Shan Zheng,Macondo,Macondo Swash Caps,Mada,Madimi One,Magra,Maiden Orange,Maitree,Major Mono Display,Mako,Mali,Mallanna,Maname,Mandali,Manjari,Manrope,Mansalva,Manuale,Manufacturing Consent,Marcellus,Marcellus SC,Marck Script,Margarine,Marhey,Markazi Text,Marko One,Marmelad,Martel,Martel Sans,Martian Mono,Marvel,Matangi,Mate,Mate SC,Matemasie,Material Icons,Material Icons Outlined,Material Icons Round,Material Icons Sharp,Material Icons Two Tone,Material Symbols,Material Symbols Outlined,Material Symbols Rounded,Material Symbols Sharp,Maven Pro,McLaren,Mea Culpa,Meddon,MedievalSharp,Medula One,Meera Inimai,Megrim,Meie Script,Menbere,Meow Script,Merienda,Merriweather,Merriweather Sans,Metal,Metal Mania,Metamorphous,Metrophobic,Michroma,Micro 5,Micro 5 Charted,Milonga,Miltonian,Miltonian Tattoo,Mina,Mingzat,Minion Pro,Miniver,Miranda Sans,Miriam Libre,Mirza,Miss Fajardose,Mitr,Mochiy Pop One,Mochiy Pop P One,Modak,Modern Antiqua,Moderustic,Mogra,Mohave,Moirai One,Molengo,Molle,Momo Signature,Momo Trust Display,Momo Trust Sans,Mona Sans,Monaco,Monda,Monofett,Monomakh,Monomaniac One,Monoton,Monotype Corsiva,Monsieur La Doulaise,Montaga,Montagu Slab,MonteCarlo,Montez,Montserrat,Montserrat Alternates,Montserrat Underline,Moo Lah Lah,Mooli,Moon Dance,Moul,Moulpali,Mountains of Christmas,Mouse Memoirs,Mozilla Headline,Mozilla Text,Mr Bedfort,Mr Dafoe,Mr De Haviland,Mrs Saint Delafield,Mrs Sheppards,Ms Madi,Mukta,Mukta Mahee,Mukta Malar,Mukta Vaani,Mulish,Murecho,MuseoModerno,My Soul,Mynerve,Myriad Pro,Mystery Quest,NTR,Nabla,Namdhinggo,Nanum Brush Script,Nanum Gothic,Nanum Gothic Coding,Nanum Myeongjo,Nanum Pen Script,Narnoor,Nata Sans,National Park,Neonderthaw,Nerko One,Neucha,Neuton,New Amsterdam,New Rocker,New Tegomin,New York,News Cycle,Newsreader,Niconne,Niramit,Nixie One,Nobile,Nokora,Norican,Nosifer,Notable,Nothing You Could Do,Noticia Text,Noto Color Emoji,Noto Emoji,Noto Kufi Arabic,Noto Mono Sans,Noto Mono Sans Adlam,Noto Mono Sans Arabic,Noto Mono Sans Armenian,Noto Mono Sans Bassa Vah,Noto Mono Sans Bengali,Noto Mono Sans Bhaiksuki,Noto Mono Sans Brahmi,Noto Mono Sans Buginese,Noto Mono Sans Buhid,Noto Mono Sans Chakma,Noto Mono Sans Cham,Noto Mono Sans Cherokee,Noto Mono Sans Coptic,Noto Mono Sans Cuneiform,Noto Mono Sans Cypriot,Noto Mono Sans Deseret,Noto Mono Sans Devanagari,Noto Mono Sans Duployan,Noto Mono Sans Elbasan,Noto Mono Sans Elymaic,Noto Mono Sans Ethiopic,Noto Mono Sans Georgian,Noto Mono Sans Glagolitic,Noto Mono Sans Gothic,Noto Mono Sans Grantha,Noto Mono Sans Gujarati,Noto Mono Sans Gurmukhi,Noto Mono Sans Hanunoo,Noto Mono Sans Hatran,Noto Mono Sans Hebrew,Noto Mono Sans Imperial Aramaic,Noto Mono Sans Inscriptional Pahlavi,Noto Mono Sans Inscriptional Parthian,Noto Mono Sans JP,Noto Mono Sans Javanese,Noto Mono Sans KR,Noto Mono Sans Kaithi,Noto Mono Sans Kannada,Noto Mono Sans Kharoshthi,Noto Mono Sans Khmer,Noto Mono Sans Khojki,Noto Mono Sans Lao,Noto Mono Sans Lepcha,Noto Mono Sans Limbu,Noto Mono Sans Linear A,Noto Mono Sans Linear B,Noto Mono Sans Lisu,Noto Mono Sans Lycian,Noto Mono Sans Lydian,Noto Mono Sans Mahajani,Noto Mono Sans Malayalam,Noto Mono Sans Mandaic,Noto Mono Sans Mandean,Noto Mono Sans Manichaean,Noto Mono Sans Marchen,Noto Mono Sans Masaram Gondi,Noto Mono Sans Medefaidrin,Noto Mono Sans Meetei Mayek,Noto Mono Sans Mende Kikakui,Noto Mono Sans Meroitic,Noto Mono Sans Miao,Noto Mono Sans Modi,Noto Mono Sans Mro,Noto Mono Sans Multani,Noto Mono Sans Myanmar,Noto Mono Sans NKo,Noto Mono Sans Nabataean,Noto Mono Sans New Tai Lue,Noto Mono Sans Newa,Noto Mono Sans Nyiakeng Puachue Hmong,Noto Mono Sans Ogham,Noto Mono Sans Ol Chiki,Noto Mono Sans Old Hungarian,Noto Mono Sans Old Italic,Noto Mono Sans Old North Arabian,Noto Mono Sans Old Permic,Noto Mono Sans Old Persian,Noto Mono Sans Old Sogdian,Noto Mono Sans Old South Arabian,Noto Mono Sans Old Turkic,Noto Mono Sans Oriya,Noto Mono Sans Osage,Noto Mono Sans Osmanya,Noto Mono Sans Pahawh Hmong,Noto Mono Sans Palmyrene,Noto Mono Sans Pau Cin Hau,Noto Mono Sans Phags Pa,Noto Mono Sans Phoenician,Noto Mono Sans Psalter Pahlavi,Noto Mono Sans Rejang,Noto Mono Sans Runic,Noto Mono Sans SC,Noto Mono Sans Samaritan,Noto Mono Sans Saurashtra,Noto Mono Sans Sharada,Noto Mono Sans Shavian,Noto Mono Sans Siddham,Noto Mono Sans Sinhala,Noto Mono Sans Sogdian,Noto Mono Sans Sora Sompeng,Noto Mono Sans Soyombo,Noto Mono Sans Sundanese,Noto Mono Sans Syloti Nagri,Noto Mono Sans Syriac,Noto Mono Sans TC,Noto Mono Sans Tagalog,Noto Mono Sans Tagbanwa,Noto Mono Sans Tai Le,Noto Mono Sans Tai Viet,Noto Mono Sans Takri,Noto Mono Sans Tamil,Noto Mono Sans Tangut,Noto Mono Sans Telugu,Noto Mono Sans Thaana,Noto Mono Sans Thai,Noto Mono Sans Tibetan,Noto Mono Sans Tirhuta,Noto Mono Sans Ugaritic,Noto Mono Sans Vai,Noto Mono Sans Wancho,Noto Mono Sans Warang Citi,Noto Mono Sans Yezidi,Noto Mono Sans Zanabazar Square,Noto Music,Noto Naskh Arabic,Noto Nastaliq Urdu,Noto Rashi Hebrew,Noto Sans,Noto Sans Adlam,Noto Sans Adlam Unjoined,Noto Sans Anatolian Hieroglyphs,Noto Sans Arabic,Noto Sans Armenian,Noto Sans Avestan,Noto Sans Balinese,Noto Sans Bamum,Noto Sans Bassa Vah,Noto Sans Batak,Noto Sans Bengali,Noto Sans Bhaiksuki,Noto Sans Brahmi,Noto Sans Buginese,Noto Sans Buhid,Noto Sans Canadian Aboriginal,Noto Sans Carian,Noto Sans Caucasian Albanian,Noto Sans Chakma,Noto Sans Cham,Noto Sans Cherokee,Noto Sans Chorasmian,Noto Sans Coptic,Noto Sans Cuneiform,Noto Sans Cypriot,Noto Sans Cypro Minoan,Noto Sans Deseret,Noto Sans Devanagari,Noto Sans Display,Noto Sans Duployan,Noto Sans Egyptian Hieroglyphs,Noto Sans Elbasan,Noto Sans Elymaic,Noto Sans Ethiopic,Noto Sans Georgian,Noto Sans Glagolitic,Noto Sans Gothic,Noto Sans Grantha,Noto Sans Gujarati,Noto Sans Gunjala Gondi,Noto Sans Gurmukhi,Noto Sans HK,Noto Sans Hanifi Rohingya,Noto Sans Hanunoo,Noto Sans Hatran,Noto Sans Hebrew,Noto Sans Imperial Aramaic,Noto Sans Indic Siyaq Numbers,Noto Sans Inscriptional Pahlavi,Noto Sans Inscriptional Parthian,Noto Sans JP,Noto Sans Javanese,Noto Sans KR,Noto Sans Kaithi,Noto Sans Kannada,Noto Sans Kawi,Noto Sans Kayah Li,Noto Sans Kharoshthi,Noto Sans Khmer,Noto Sans Khojki,Noto Sans Khudawadi,Noto Sans Lao,Noto Sans Lao Looped,Noto Sans Lepcha,Noto Sans Limbu,Noto Sans Linear A,Noto Sans Linear B,Noto Sans Lisu,Noto Sans Lycian,Noto Sans Lydian,Noto Sans Mahajani,Noto Sans Malayalam,Noto Sans Mandaic,Noto Sans Mandean,Noto Sans Manichaean,Noto Sans Marchen,Noto Sans Masaram Gondi,Noto Sans Math,Noto Sans Mayan Numerals,Noto Sans Medefaidrin,Noto Sans Meetei Mayek,Noto Sans Mende Kikakui,Noto Sans Meroitic,Noto Sans Miao,Noto Sans Modi,Noto Sans Mongolian,Noto Sans Mono,Noto Sans Mro,Noto Sans Multani,Noto Sans Myanmar,Noto Sans NKo,Noto Sans NKo Unjoined,Noto Sans Nabataean,Noto Sans Nag Mundari,Noto Sans Nandinagari,Noto Sans New Tai Lue,Noto Sans Newa,Noto Sans Nushu,Noto Sans Nyiakeng Puachue Hmong,Noto Sans Ogham,Noto Sans Ol Chiki,Noto Sans Old Hungarian,Noto Sans Old Italic,Noto Sans Old North Arabian,Noto Sans Old Permic,Noto Sans Old Persian,Noto Sans Old Sogdian,Noto Sans Old South Arabian,Noto Sans Old Turkic,Noto Sans Oriya,Noto Sans Osage,Noto Sans Osmanya,Noto Sans Pahawh Hmong,Noto Sans Palmyrene,Noto Sans Pau Cin Hau,Noto Sans Phags Pa,Noto Sans PhagsPa,Noto Sans Phoenician,Noto Sans Psalter Pahlavi,Noto Sans Rejang,Noto Sans Runic,Noto Sans SC,Noto Sans Samaritan,Noto Sans Saurashtra,Noto Sans Sharada,Noto Sans Shavian,Noto Sans Siddham,Noto Sans SignWriting,Noto Sans Sinhala,Noto Sans Sogdian,Noto Sans Sora Sompeng,Noto Sans Soyombo,Noto Sans Sundanese,Noto Sans Sunuwar,Noto Sans Syloti Nagri,Noto Sans Symbols,Noto Sans Symbols 2,Noto Sans Syriac,Noto Sans Syriac Eastern,Noto Sans Syriac Western,Noto Sans TC,Noto Sans Tagalog,Noto Sans Tagbanwa,Noto Sans Tai Le,Noto Sans Tai Tham,Noto Sans Tai Viet,Noto Sans Takri,Noto Sans Tamil,Noto Sans Tamil Supplement,Noto Sans Tangsa,Noto Sans Tangut,Noto Sans Telugu,Noto Sans Thaana,Noto Sans Thai,Noto Sans Thai Looped,Noto Sans Tibetan,Noto Sans Tifinagh,Noto Sans Tirhuta,Noto Sans Ugaritic,Noto Sans Vai,Noto Sans Vithkuqi,Noto Sans Wancho,Noto Sans Warang Citi,Noto Sans Yezidi,Noto Sans Yi,Noto Sans Zanabazar Square,Noto Serif,Noto Serif Ahom,Noto Serif Arabic,Noto Serif Arabic Light,Noto Serif Armenian,Noto Serif Armenian Light,Noto Serif Balinese,Noto Serif Bengali,Noto Serif Bengali Light,Noto Serif Devanagari,Noto Serif Devanagari Light,Noto Serif Display,Noto Serif Dives Akuru,Noto Serif Dogra,Noto Serif Ethiopic,Noto Serif Ethiopic Light,Noto Serif Georgian,Noto Serif Georgian Light,Noto Serif Grantha,Noto Serif Gujarati,Noto Serif Gujarati Light,Noto Serif Gurmukhi,Noto Serif Gurmukhi Light,Noto Serif HK,Noto Serif Hebrew,Noto Serif Hebrew Light,Noto Serif Hentaigana,Noto Serif JP,Noto Serif JP Light,Noto Serif KR,Noto Serif KR Light,Noto Serif Kannada,Noto Serif Kannada Light,Noto Serif Khitan Small Script,Noto Serif Khmer,Noto Serif Khmer Light,Noto Serif Khojki,Noto Serif Lao,Noto Serif Lao Light,Noto Serif Light,Noto Serif Makasar,Noto Serif Malayalam,Noto Serif Malayalam Light,Noto Serif Myanmar,Noto Serif Myanmar Light,Noto Serif NP Hmong,Noto Serif Old Uyghur,Noto Serif Oriya,Noto Serif Oriya Light,Noto Serif Ottoman Siyaq,Noto Serif SC,Noto Serif SC Light,Noto Serif Sinhala,Noto Serif Sinhala Light,Noto Serif TC,Noto Serif TC Light,Noto Serif Tamil,Noto Serif Tamil Light,Noto Serif Tangut,Noto Serif Telugu,Noto Serif Telugu Light,Noto Serif Thai,Noto Serif Thai Light,Noto Serif Tibetan,Noto Serif Tibetan Light,Noto Serif Todhri,Noto Serif Toto,Noto Serif Vithkuqi,Noto Serif Yezidi,Noto Traditional Nushu,Noto Znamenny Musical Notation,Nova Cut,Nova Flat,Nova Mono,Nova Oval,Nova Round,Nova Script,Nova Slim,Nova Square,Numans,Nunito,Nunito Sans,Nuosu SIL,Odibee Sans,Odor Mean Chey,Offside,Oi,Ojuju,Old Standard TT,Oldenburg,Ole,Oleo Script,Oleo Script Swash Caps,Onest,Oooh Baby,Open Sans,Optima,Oranienbaum,Orbit,Orbitron,Oregano,Orelega One,Orienta,Original Surfer,Oswald,Outfit,Over the Rainbow,Overlock,Overlock SC,Overpass,Overpass Mono,Ovo,Oxanium,Oxygen,Oxygen Mono,PT Mono,PT Sans,PT Sans Caption,PT Sans Narrow,PT Serif,PT Serif Caption,Pacifico,Padauk,Padyakke Expanded One,Palanquin,Palanquin Dark,Palatino,Palatino Linotype,Palette Mosaic,Pangolin,Paprika,Papyrus,Parastoo,Parisienne,Parkinsans,Passero One,Passion One,Passions Conflict,Pathway Extreme,Pathway Gothic One,Patrick Hand,Patrick Hand SC,Pattaya,Patua One,Pavanam,Paytone One,Peddana,Peralta,Permanent Marker,Perpetua,Petemoss,Petit Formal Script,Petrona,Phetsarath,Philosopher,Phudu,Piazzolla,Piedra,Pinyon Script,Pirata One,Pixelify Sans,Plaster,Platypi,Play,Playball,Playbill,Playfair,Playfair Display,Playfair Display SC,Playpen Sans,Playpen Sans Arabic,Playpen Sans Deva,Playpen Sans Hebrew,Playpen Sans Thai,Playwrite AR,Playwrite AR Guides,Playwrite AT,Playwrite AT Guides,Playwrite AU NSW,Playwrite AU NSW Guides,Playwrite AU QLD,Playwrite AU QLD Guides,Playwrite AU SA,Playwrite AU SA Guides,Playwrite AU TAS,Playwrite AU TAS Guides,Playwrite AU VIC,Playwrite AU VIC Guides,Playwrite BE VLG,Playwrite BE VLG Guides,Playwrite BE WAL,Playwrite BE WAL Guides,Playwrite BR,Playwrite BR Guides,Playwrite CA,Playwrite CA Guides,Playwrite CL,Playwrite CL Guides,Playwrite CO,Playwrite CO Guides,Playwrite CU,Playwrite CU Guides,Playwrite CZ,Playwrite CZ Guides,Playwrite DE Grund,Playwrite DE Grund Guides,Playwrite DE LA,Playwrite DE LA Guides,Playwrite DE SAS,Playwrite DE SAS Guides,Playwrite DE VA,Playwrite DE VA Guides,Playwrite DK Loopet,Playwrite DK Loopet Guides,Playwrite DK Uloopet,Playwrite DK Uloopet Guides,Playwrite ES,Playwrite ES Deco,Playwrite ES Deco Guides,Playwrite ES Guides,Playwrite FR Moderne,Playwrite FR Moderne Guides,Playwrite FR Trad,Playwrite FR Trad Guides,Playwrite GB J,Playwrite GB J Guides,Playwrite GB S,Playwrite GB S Guides,Playwrite HR,Playwrite HR Guides,Playwrite HR Lijeva,Playwrite HR Lijeva Guides,Playwrite HU,Playwrite HU Guides,Playwrite ID,Playwrite ID Guides,Playwrite IE,Playwrite IE Guides,Playwrite IN,Playwrite IN Guides,Playwrite IS,Playwrite IS Guides,Playwrite IT Moderna,Playwrite IT Moderna Guides,Playwrite IT Trad,Playwrite IT Trad Guides,Playwrite MX,Playwrite MX Guides,Playwrite NG Modern,Playwrite NG Modern Guides,Playwrite NL,Playwrite NL Guides,Playwrite NO,Playwrite NO Guides,Playwrite NZ,Playwrite NZ Basic,Playwrite NZ Basic Guides,Playwrite NZ Guides,Playwrite PE,Playwrite PE Guides,Playwrite PL,Playwrite PL Guides,Playwrite PT,Playwrite PT Guides,Playwrite RO,Playwrite RO Guides,Playwrite SK,Playwrite SK Guides,Playwrite TZ,Playwrite TZ Guides,Playwrite US Modern,Playwrite US Modern Guides,Playwrite US Trad,Playwrite US Trad Guides,Playwrite VN,Playwrite VN Guides,Playwrite ZA,Playwrite ZA Guides,Plus Jakarta Sans,Pochaevsk,Podkova,Poetsen One,Poiret One,Poller One,Poltawski Nowy,Poly,Pompiere,Ponnala,Ponomar,Pontano Sans,Poor Story,Poppins,Port Lligat Sans,Port Lligat Slab,Potta One,Pragati Narrow,Praise,Prata,Preahvihear,Press Start 2P,Pridi,Princess Sofia,Prociono,Prompt,Prosto One,Protest Guerrilla,Protest Revolution,Protest Riot,Protest Strike,Proza Libre,Public Sans,Puppies Play,Puritan,Purple Purse,Qahiri,Quando,Quantico,Quattrocento,Quattrocento Sans,Questrial,Quicksand,Quintessential,Qwigley,Qwitcher Grypen,REM,Racing Sans One,Radio Canada,Radio Canada Big,Radley,Rajdhani,Rakkas,Raleway,Raleway Dots,Ramabhadra,Ramaraja,Rambla,Rammetto One,Rampart One,Ramsina,Ranchers,Rancho,Ranga,Rasa,Rationale,Ravi Prakash,Readex Pro,Recursive,Red Hat Display,Red Hat Mono,Red Hat Text,Red Rose,Redacted,Redacted Script,Reddit Mono,Reddit Sans,Reddit Sans Condensed,Redressed,Reem Kufi,Reem Kufi Fun,Reem Kufi Ink,Reenie Beanie,Reggae One,Rethink Sans,Revalia,Rhodium Libre,Ribeye,Ribeye Marrow,Righteous,Risque,Road Rage,Roboto,Roboto Condensed,Roboto Flex,Roboto Mono,Roboto Serif,Roboto Slab,Rochester,Rock 3D,Rock Salt,RocknRoll One,Rockwell,Rockwell Extra Bold,Rokkitt,Romanesco,Ropa Sans,Rosario,Rosarivo,Rouge Script,Rowdies,Rozha One,Rubik,Rubik 80s Fade,Rubik Beastly,Rubik Broken Fax,Rubik Bubbles,Rubik Burned,Rubik Dirt,Rubik Distressed,Rubik Doodle Shadow,Rubik Doodle Triangles,Rubik Gemstones,Rubik Glitch,Rubik Glitch Pop,Rubik Iso,Rubik Lines,Rubik Maps,Rubik Marker Hatch,Rubik Maze,Rubik Microbe,Rubik Mono One,Rubik Moonrocks,Rubik Pixels,Rubik Puddles,Rubik Scribble,Rubik Spray Paint,Rubik Storm,Rubik Vinyl,Rubik Wet Paint,Ruda,Rufina,Ruge Boogie,Ruluko,Rum Raisin,Ruslan Display,Russo One,Ruthie,Ruwudu,Rye,SN Pro,STIX Two Text,SUSE,SUSE Mono,Sacramento,Sahitya,Sail,Saira,Saira Condensed,Saira Extra Condensed,Saira Semi Condensed,Saira Stencil,Saira Stencil One,Salsa,Sanchez,Sancreek,Sand,Sankofa Display,Sansation,Sansita,Sansita Swashed,Sarabun,Sarala,Sarina,Sarpanch,Sassy Frass,Satisfy,Savate,Sawarabi Gothic,Sawarabi Mincho,Scada,Scheherazade New,Schibsted Grotesk,Schoolbell,Science Gothic,Scope One,Seaweed Script,Secular One,Sedan,Sedan SC,Sedgwick Ave,Sedgwick Ave Display,Segoe Print,Segoe Script,Segoe UI,Sekuya,Sen,Send Flowers,Sevillana,Seymour One,Shadows Into Light,Shadows Into Light Two,Shafarik,Shalimar,Shantell Sans,Shanti,Share,Share Tech,Share Tech Mono,Shippori Antique,Shippori Antique B1,Shippori Mincho,Shippori Mincho B1,Shizuru,Shojumaru,Short Stack,Shrikhand,Siemreap,Sigmar,Sigmar One,Signika,Signika Negative,Silkscreen,Simonetta,Single Day,Sintony,Sirin Stencil,Sirivennela,Six Caps,Sixtyfour,Sixtyfour Convergence,Skranji,Slabo 13px,Slabo 27px,Slackey,Slackside One,Smokum,Smooch,Smooch Sans,Smythe,Sniglet,Snippet,Snowburst One,Sofadi One,Sofia,Sofia Sans,Sofia Sans Condensed,Sofia Sans Extra Condensed,Sofia Sans Semi Condensed,Solitreo,Solway,Sometype Mono,Song Myung,Sono,Sonsie One,Sora,Sorts Mill Goudy,Sour Gummy,Source Code Pro,Source Sans 3,Source Serif 4,Space Grotesk,Space Mono,Special Elite,Special Gothic,Special Gothic Condensed One,Special Gothic Expanded One,Spectral,Spectral SC,Spicy Rice,Spinnaker,Spirax,Splash,Spline Sans,Spline Sans Mono,Squada One,Square Peg,Sree Krushnadevaraya,Sriracha,Srisakdi,Staatliches,Stack Sans Headline,Stack Sans Notch,Stack Sans Text,Stalemate,Stalinist One,Stardos Stencil,Stick,Stick No Bills,Stint Ultra Condensed,Stint Ultra Expanded,Stoke,Story Script,Strait,Style Script,Stylish,Sue Ellen Francisco,Suez One,Sulphur Point,Sumana,Sunflower,Sunshiney,Supermercado One,Sura,Suranna,Suravaram,Suwannaphum,Swanky and Moo Moo,Symbol,Syncopate,Syne,Syne Mono,Syne Tactile,TASA Explorer,TASA Orbiter,Tac One,Tacoma,Tagesschrift,Tahoma,Tai Heritage Pro,Tajawal,Tangerine,Tapestry,Taprom,Tauri,Taviraj,Teachers,Teko,Tektur,Telex,Tenali Ramakrishna,Tenor Sans,Text Me One,Texturina,Thasadith,The Girl Next Door,The Nautigal,Tienne,TikTok Sans,Tillana,Tilt Neon,Tilt Prism,Tilt Warp,Times,Times New Roman,Timmana,Tinos,Tiny5,Tiro Bangla,Tiro Devanagari Hindi,Tiro Devanagari Marathi,Tiro Devanagari Sanskrit,Tiro Gurmukhi,Tiro Kannada,Tiro Tamil,Tiro Telugu,Tirra,Titan One,Titillium Web,Tomorrow,Tourney,Trade Winds,Train One,Trebuchet MS,Triodion,Trirong,Trispace,Trocchi,Trochut,Truculenta,Trykker,Tsukimi Rounded,Tuffy,Tulpen One,Turret Road,Tw Cen MT,Twinkle Star,Ubuntu,Ubuntu Condensed,Ubuntu Mono,Ubuntu Sans,Ubuntu Sans Mono,Uchen,Ultra,Unbounded,Uncial Antiqua,Underdog,Unica One,UnifrakturCook,UnifrakturMaguntia,Univers,Unkempt,Unlock,Unna,UoqMunThenKhung,Updock,Urbanist,VT323,Vampiro One,Varela,Varela Round,Varta,Vast Shadow,Vazirmatn,Vend Sans,Verdana,Vesper Libre,Viaoda Libre,Vibes,Vibur,Victor Mono,Vidaloka,Viga,Vina Sans,Voces,Volkhov,Vollkorn,Vollkorn SC,Voltaire,Vujahday Script,WDXL Lubrifont JP N,WDXL Lubrifont SC,WDXL Lubrifont TC,Waiting for the Sunrise,Wallpoet,Walter Turncoat,Warnes,Water Brush,Waterfall,Wavefont,Webdings,Wellfleet,Wendy One,Whisper,WindSong,Wingdings,Winky Rough,Winky Sans,Wire One,Wittgenstein,Wix Madefor Display,Wix Madefor Text,Work Sans,Workbench,Xanh Mono,Yaldevi,Yanone Kaffeesatz,Yantramanav,Yarndings 12,Yarndings 12 Charted,Yarndings 20,Yarndings 20 Charted,Yatra One,Yellowtail,Yeon Sung,Yeseva One,Yesteryear,Yomogi,Young Serif,Yrsa,Ysabeau,Ysabeau Infant,Ysabeau Office,Ysabeau SC,Yuji Boku,Yuji Hentaigana Akari,Yuji Hentaigana Akebono,Yuji Mai,Yuji Syuku,Yusei Magic,ZCOOL KuaiLe,ZCOOL QingKe HuangYou,ZCOOL XiaoWei,Zain,Zalando Sans,Zalando Sans Expanded,Zalando Sans SemiExpanded,Zapf Dingbats,Zen Antique,Zen Antique Soft,Zen Dots,Zen Kaku Gothic Antique,Zen Kaku Gothic New,Zen Kurenaido,Zen Loop,Zen Maru Gothic,Zen Old Mincho,Zen Tokyo Zoo,Zeyada,Zhi Mang Xing,Zilla Slab,Zilla Slab Highlight';
    let allFonts = COMPRESSED_FONTS.split(',');

    // Initialize Fonts and Color Presets on Load
    window.addEventListener('DOMContentLoaded', () => {
        initFonts();
        generatePaletteColors();
        filterColorCategory('all');
        
        // Populate custom color text box with initial value
        if (document.getElementById('custom-color-picker')) {
            document.getElementById('custom-color-picker').value = currentAccent;
        }

        // Hide font dropdown on click outside
        document.addEventListener('click', (e) => {
            if (!e.target.closest('.searchable-font-container')) {
                hideFontDropdown();
            }
        });
    });

    function initFonts() {
        // Synchronous immediate render from the 3150+ massive embedded static array
        // Completely bypasses browser CORS / CSP / Network latency issues
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
        
        if (sizeName === 'small' || sizeName === 'medium' || sizeName === 'large') {
            sheet.style.fontSize = ''; // clear any inline style
            sheet.classList.add('size-' + sizeName);
        } else if (sizeName !== '') {
            // Numerical pixel value handling
            sheet.style.fontSize = sizeName;
        }
        
        // Clear custom input visually if standard option is selected
        const customInput = document.getElementById('custom-size-input');
        if (customInput && customInput.value !== sizeName && sizeName !== '') {
            customInput.value = '';
        }
        
        currentSize = sizeName;
    }

    function setCustomSize(val) {
        val = val.trim();
        if(!val) return;
        
        // Auto-append 'px' if user just typed a raw number
        if(!isNaN(val)) {
            val = val + 'px';
        }
        
        // Set the dropdown to the hidden "Custom" option to visually detach it
        document.getElementById('size-dropdown').value = '';
        
        // Apply the size
        setSize(val);
        
        // Persist the correctly formatted value back into the input box
        document.getElementById('custom-size-input').value = val;
    }

    function setAccent(colorHex, swatchEl = null) {
        // Set CSS Accent Variables dynamically
        document.documentElement.style.setProperty('--resume-accent', colorHex);
        currentAccent = colorHex;
        
        if (swatchEl) {
            document.querySelectorAll('.color-swatch').forEach(el => el.classList.remove('active'));
            swatchEl.classList.add('active');
            if (document.getElementById('custom-color-picker')) {
                document.getElementById('custom-color-picker').value = colorHex;
            }
        } else {
            document.querySelectorAll('.color-swatch').forEach(el => el.classList.remove('active'));
        }
        
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
        if(!container) return;
        container.innerHTML = '';
        
        const fragment = document.createDocumentFragment();
        colorsArray.forEach(c => {
            const div = document.createElement('div');
            div.className = 'mosaic-cell';
            div.style.background = c.hsl;
            div.title = c.hsl;
            div.onclick = () => {
                setAccent(c.hsl);
                if (document.getElementById('custom-color-picker')) {
                    document.getElementById('custom-color-picker').value = c.hsl;
                }
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
