import os

def main():
    blade_path = "resources/views/backEnd/studentPanel/resume_creator.blade.php"
    with open(blade_path, "r", encoding="utf-8") as f:
        content = f.read()

    with open("compressed_fonts_js.txt", "r", encoding="utf-8") as f:
        compressed_fonts = f.read().strip()

    # The exact block to replace
    target_start = """    // 2000+ Fonts Searchable Autocomplete Dropdown State
    let allFonts = [];
    const fallbackFonts = [
        "Inter", "Montserrat", "Georgia", "Merriweather", "Roboto", "Open Sans", "Lato", "Poppins", 
        "Oswald", "Raleway", "Ubuntu", "Nunito", "Playfair Display", "Lora", "Fira Sans", 
        "Quicksand", "PT Sans", "Josefin Sans", "Cabin", "Rubik", "Kanit", "Mulish", 
        "Inconsolata", "Work Sans", "Arvo", "Cinzel", "Maven Pro", "Dosis", "Comfortaa", "Pacifico"
    ];"""

    target_end = """        renderFontDropdown(allFonts);
        document.getElementById('font-search-input').value = currentFont;
    }"""

    # Extract the block
    start_idx = content.find(target_start)
    end_idx = content.find(target_end, start_idx) + len(target_end)

    if start_idx == -1 or end_idx == -1:
        print("Could not find the target block to replace.")
        return

    replacement = f"""    // 3000+ Fonts Searchable Autocomplete Dropdown State
    {compressed_fonts}
    let allFonts = COMPRESSED_FONTS.split(',');

    // Initialize Fonts and Color Presets on Load
    window.addEventListener('DOMContentLoaded', () => {{
        initFonts();
        generatePaletteColors();
        filterColorCategory('all');
        
        // Populate custom color text box with initial value
        document.getElementById('custom-color-text').value = currentAccent;
        document.getElementById('color-valid-indicator').style.background = currentAccent;

        // Hide font dropdown on click outside
        document.addEventListener('click', (e) => {{
            if (!e.target.closest('.searchable-font-container')) {{
                hideFontDropdown();
            }}
        }});
    }});

    function initFonts() {{
        // Synchronous immediate render from the 3150+ massive embedded static array
        // Completely bypasses browser CORS / CSP / Network latency issues
        renderFontDropdown(allFonts);
        document.getElementById('font-search-input').value = currentFont;
    }}"""

    new_content = content[:start_idx] + replacement + content[end_idx:]

    with open(blade_path, "w", encoding="utf-8") as f:
        f.write(new_content)

    print("Successfully injected the 3000+ fonts into resume_creator.blade.php!")

if __name__ == "__main__":
    main()
