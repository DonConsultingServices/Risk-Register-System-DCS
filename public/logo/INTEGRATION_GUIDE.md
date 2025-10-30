# DCS Logo Integration Guide for Laravel

## 🚀 **Quick Start**

### 1. **Include Logo Colors in Any View**
```php
// In your Blade template
<link href="{{ asset('logo/logo-colors.css') }}" rel="stylesheet">
```

### 2. **Use CSS Variables**
```css
.my-element {
    background-color: var(--logo-dark-blue-primary);
    color: var(--logo-white);
    border: 2px solid var(--logo-red);
}
```

### 3. **Use Utility Classes**
```html
<button class="btn-logo-primary">Primary Button</button>
<div class="bg-logo-gradient">Gradient Background</div>
<p class="text-logo-red">Important Text</p>
```

## 🎨 **Color Usage Examples**

### **Primary Actions & Buttons**
```html
<!-- Primary buttons -->
<button class="btn btn-primary">Save Changes</button>
<button class="btn-logo-primary">Custom Primary</button>

<!-- Secondary actions -->
<button class="btn btn-outline-primary">Cancel</button>
<button class="btn-logo-primary" style="background: transparent; color: var(--logo-dark-blue-primary);">Outline</button>
```

### **Forms & Inputs**
```html
<div class="form-group">
    <label class="text-logo-primary">Email Address</label>
    <input type="email" class="form-control input-logo" placeholder="Enter email">
</div>

<!-- Form validation -->
<div class="alert alert-danger">
    <i class="fas fa-exclamation-circle text-logo-red"></i>
    Please enter a valid email address.
</div>
```

### **Cards & Containers**
```html
<div class="card">
    <div class="card-header bg-logo-light-bg">
        <h5 class="text-logo-primary mb-0">Risk Assessment</h5>
    </div>
    <div class="card-body">
        <p class="text-logo-secondary">Risk details and analysis...</p>
    </div>
</div>
```

### **Navigation & Sidebar**
```html
<nav class="sidebar">
    <div class="nav-item">
        <a class="nav-link active" href="#">
            <i class="fas fa-dashboard"></i>
            Dashboard
        </a>
    </div>
</nav>
```

## 🔧 **Laravel Blade Integration**

### **Using PHP Constants**
```php
@php
    include_once(public_path('logo/logo-include.php'));
@endphp

<style>
    .custom-element {
        background-color: {{ LOGO_DARK_BLUE_PRIMARY }};
        color: {{ LOGO_WHITE }};
    }
</style>
```

### **Using Helper Functions**
```php
@php
    include_once(public_path('logo/logo-include.php'));
    includeLogoCSS();
@endphp

<div class="logo-container">
    @php displayLogo('DCS Logo', 'logo-image', '200', '67') @endphp
</div>
```

### **Dynamic Color Application**
```php
@php
    $riskLevel = 'high';
    $colorClass = $riskLevel === 'high' ? 'text-logo-red' : 'text-logo-primary';
@endphp

<span class="badge {{ $colorClass }}">{{ ucfirst($riskLevel) }} Risk</span>
```

## 📱 **Responsive Design with Logo Colors**

### **Mobile-First Approach**
```css
/* Base styles */
.risk-card {
    background: var(--logo-white);
    border: 2px solid var(--logo-medium-bg);
}

/* Tablet and up */
@media (min-width: 768px) {
    .risk-card {
        border-color: var(--logo-dark-blue-primary);
    }
}

/* Desktop */
@media (min-width: 1024px) {
    .risk-card:hover {
        border-color: var(--logo-red);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
    }
}
```

### **Dark Mode Support**
```css
@media (prefers-color-scheme: dark) {
    :root {
        --logo-light-bg: #1e293b;
        --logo-medium-bg: #334155;
        --logo-text-primary: #f8fafc;
        --logo-text-secondary: #e2e8f0;
    }
}
```

## 🎯 **Component-Specific Usage**

### **Risk Priority Indicators**
```html
<!-- High Risk -->
<div class="risk-indicator risk-priority-high">
    <i class="fas fa-exclamation-triangle text-logo-red"></i>
    <span class="text-logo-red">High Risk</span>
</div>

<!-- Medium Risk -->
<div class="risk-indicator risk-priority-medium">
    <i class="fas fa-exclamation-circle text-logo-primary"></i>
    <span class="text-logo-primary">Medium Risk</span>
</div>

<!-- Low Risk -->
<div class="risk-indicator risk-priority-low">
    <i class="fas fa-info-circle text-logo-secondary"></i>
    <span class="text-logo-secondary">Low Risk</span>
</div>
```

### **Status Badges**
```html
<span class="badge bg-success text-white">Active</span>
<span class="badge bg-danger text-white">Critical</span>
<span class="badge bg-warning text-dark">Pending</span>
<span class="badge bg-info text-white">In Review</span>
```

### **Data Tables**
```html
<table class="table table-striped">
    <thead class="bg-logo-light-bg">
        <tr>
            <th class="text-logo-primary">Client Name</th>
            <th class="text-logo-primary">Risk Level</th>
            <th class="text-logo-primary">Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>ABC Corp</td>
            <td><span class="badge risk-priority-high">High</span></td>
            <td><span class="badge bg-success">Active</span></td>
        </tr>
    </tbody>
</table>
```

## 🚨 **Common Mistakes to Avoid**

### ❌ **Don't Use Hardcoded Colors**
```css
/* WRONG */
.my-element { color: #1e3a8a; }

/* CORRECT */
.my-element { color: var(--logo-dark-blue-primary); }
```

### ❌ **Don't Override Bootstrap Classes**
```css
/* WRONG */
.btn-primary { background-color: #ff0000 !important; }

/* CORRECT */
.btn-primary { background-color: var(--logo-dark-blue-primary); }
```

### ❌ **Don't Forget Hover States**
```css
/* WRONG */
.my-button { background-color: var(--logo-dark-blue-primary); }

/* CORRECT */
.my-button { 
    background-color: var(--logo-dark-blue-primary);
    transition: all 0.3s ease;
}
.my-button:hover { 
    background-color: var(--logo-dark-blue-hover);
}
```

## ✅ **Best Practices**

### **1. Consistency**
- Always use logo colors for primary actions
- Use red sparingly for warnings and critical items
- Maintain consistent spacing and typography

### **2. Accessibility**
- Ensure sufficient contrast ratios
- Use color + icons for better understanding
- Test with color-blind users

### **3. Performance**
- CSS variables are cached efficiently
- Logo SVG is scalable and lightweight
- Use utility classes for common patterns

### **4. Maintenance**
- Update colors only in `logo-colors.css`
- Use semantic class names
- Document custom color combinations

## 🔍 **Testing Your Integration**

### **1. Visual Testing**
- Open `/logo/demo.html` to see all colors
- Check contrast ratios with browser dev tools
- Test on different screen sizes

### **2. Code Review**
- Ensure no hardcoded colors remain
- Verify all buttons use logo colors
- Check form focus states

### **3. User Testing**
- Test with actual users
- Verify color meanings are clear
- Check accessibility compliance

## 📞 **Need Help?**

- **Demo Page**: `/logo/demo.html`
- **Color Reference**: `/logo/README.md`
- **PHP Helpers**: `/logo/logo-include.php`
- **CSS Variables**: `/logo/logo-colors.css`

Remember: **All themes and colors must come from our logo!** 🎨✨
