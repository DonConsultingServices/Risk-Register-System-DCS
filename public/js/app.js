// DCS-Best Risk Register - JavaScript Functions

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Form validation enhancement
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Risk rating calculation
    function calculateRiskRating() {
        const likelihood = document.getElementById('risk_likelihood');
        const impact = document.getElementById('risk_impact_level');
        const clientType = document.getElementById('client_type');
        const serviceType = document.getElementById('service_type');
        const geographicalArea = document.getElementById('geographical_area');
        const deliveryChannel = document.getElementById('delivery_channel');
        const paymentMethod = document.getElementById('payment_method');

        if (likelihood && impact && clientType && serviceType && geographicalArea && deliveryChannel && paymentMethod) {
            let totalRating = 0;
            let riskAssessment = '';

            // Calculate based on selections
            // This is a simplified calculation - you can enhance this based on your business logic
            if (likelihood.value === 'Very likely') totalRating += 3;
            else if (likelihood.value === 'Likely') totalRating += 2;
            else if (likelihood.value === 'Not likely') totalRating += 1;

            if (impact.value === 'Very high') totalRating += 3;
            else if (impact.value === 'High') totalRating += 2;
            else if (impact.value === 'Medium') totalRating += 2;
            else if (impact.value === 'Low') totalRating += 1;
            else if (impact.value === 'Very low') totalRating += 1;

            // Client risk factors
            if (clientType.value === 'Legal Person') totalRating += 1;
            if (geographicalArea.value === 'Foreign client') totalRating += 2;
            else if (geographicalArea.value === 'Regional client') totalRating += 1;
            if (deliveryChannel.value === 'Non-face to face') totalRating += 1;
            if (paymentMethod.value === 'Cash') totalRating += 2;
            else if (paymentMethod.value === 'POS') totalRating += 1;

            // Determine risk assessment
            if (totalRating <= 5) {
                riskAssessment = 'Low Risk Rated Client - Accept';
            } else if (totalRating <= 8) {
                riskAssessment = 'Medium Risk Rated Client - Accept';
            } else {
                riskAssessment = 'High Risk Rated Client - Enhanced Due Diligence Required';
            }

            // Update hidden fields if they exist
            const totalRatingField = document.getElementById('total_risk_rating');
            const riskAssessmentField = document.getElementById('risk_assessment');
            
            if (totalRatingField) totalRatingField.value = totalRating;
            if (riskAssessmentField) riskAssessmentField.value = riskAssessment;

            // Show preview if element exists
            const previewElement = document.getElementById('risk-rating-preview');
            if (previewElement) {
                previewElement.innerHTML = `
                    <div class="alert alert-info">
                        <strong>Calculated Risk Rating:</strong> ${totalRating}/10<br>
                        <strong>Risk Assessment:</strong> ${riskAssessment}
                    </div>
                `;
            }
        }
    }

    // Add event listeners for risk calculation
    const riskFields = ['risk_likelihood', 'risk_impact_level', 'client_type', 'service_type', 'geographical_area', 'delivery_channel', 'payment_method'];
    riskFields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('change', calculateRiskRating);
        }
    });

    // Table row highlighting
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(function(row) {
        row.addEventListener('click', function() {
            // Remove highlight from other rows
            tableRows.forEach(r => r.classList.remove('table-active'));
            // Add highlight to clicked row
            this.classList.add('table-active');
        });
    });

    // Search functionality
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Export functionality
    const exportBtn = document.getElementById('export-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // Show loading state
            this.innerHTML = '<span class="loading"></span> Exporting...';
            this.disabled = true;
            
            // Simulate export process
            setTimeout(function() {
                exportBtn.innerHTML = '<i class="fas fa-download me-1"></i>Export CSV';
                exportBtn.disabled = false;
                // Here you would typically trigger the actual export
                alert('Export functionality would be implemented here');
            }, 2000);
        });
    }

    // Print functionality
    const printBtn = document.getElementById('print-btn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }

    // Confirmation dialogs
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this risk? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // Auto-save form data (localStorage)
    const form = document.querySelector('form');
    if (form) {
        // Load saved data
        const savedData = localStorage.getItem('riskFormData');
        if (savedData) {
            const data = JSON.parse(savedData);
            Object.keys(data).forEach(function(key) {
                const field = form.querySelector(`[name="${key}"]`);
                if (field) {
                    field.value = data[key];
                }
            });
        }

        // Save data on input
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(function(input) {
            input.addEventListener('input', function() {
                const formData = new FormData(form);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                localStorage.setItem('riskFormData', JSON.stringify(data));
            });
        });

        // Clear saved data on successful submission
        form.addEventListener('submit', function() {
            localStorage.removeItem('riskFormData');
        });
    }

    // Responsive table handling
    function handleResponsiveTables() {
        const tables = document.querySelectorAll('.table-responsive');
        tables.forEach(function(table) {
            if (table.scrollWidth > table.clientWidth) {
                table.classList.add('has-scroll');
            }
        });
    }

    // Call on load and resize
    handleResponsiveTables();
    window.addEventListener('resize', handleResponsiveTables);

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// Export to CSV function
function exportToCSV(data, filename) {
    const csvContent = "data:text/csv;charset=utf-8," + data;
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
} 