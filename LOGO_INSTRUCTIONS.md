# 🎨 DCS Logo Integration Instructions

## Current Status
- ✅ Logo placeholder is in place
- ✅ Brand styling is ready
- ⏳ Waiting for actual DCS logo

## When You Have Your Logo

### 1. Add Your Logo File
Place your DCS logo in: `public/images/dcs-logo.png`

**Recommended specifications:**
- **Format**: PNG (transparent background preferred)
- **Size**: 200x200px minimum (will be scaled down)
- **Aspect Ratio**: Square or close to square
- **Background**: Transparent or white

### 2. Update the CSS
Edit `public/css/logo-placeholder.css` and replace the placeholder styles:

**Find this section:**
```css
.logo-placeholder {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #3498db, #2980b9);
    border-radius: 8px;
    color: white;
    transition: all 0.3s ease;
}
```

**Replace with:**
```css
.logo-placeholder {
    width: 40px;
    height: 40px;
    background-image: url('../images/dcs-logo.png');
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    border-radius: 8px;
    transition: all 0.3s ease;
}
```

### 3. Update Brand Colors (Optional)
If you want to use DCS brand colors, update the CSS variables in `public/css/logo-placeholder.css`:

```css
:root {
    --dcs-primary: #your-primary-color;
    --dcs-primary-dark: #your-primary-dark-color;
    --dcs-secondary: #your-secondary-color;
    --dcs-accent: #your-accent-color;
    --dcs-success: #your-success-color;
    --dcs-warning: #your-warning-color;
}
```

### 4. Test the Integration
- Refresh your browser
- Check that the logo displays correctly
- Verify it looks good on different screen sizes
- Test the hover effects

## Current Logo Placeholder
The current placeholder shows:
- DCS building icon
- "DCS" brand name
- "Risk Register" subtitle
- Professional blue gradient background

## File Structure
```
public/
├── images/
│   └── dcs-logo.png          # ← Add your logo here
├── css/
│   ├── style.css
│   └── logo-placeholder.css  # ← Update this file
└── index.php
```

## Need Help?
If you need assistance with logo integration or have questions about the specifications, just let me know!

---

**Status**: Ready for DCS logo integration 🎨 