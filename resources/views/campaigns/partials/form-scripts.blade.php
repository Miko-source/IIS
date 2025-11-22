<script>
    (function() {
        if (window.initCampaignForm) return;

        window.initCampaignForm = function(config) {
            const {
                formId,
                nameId,
                nameErrorId,
                startDisplayId,
                startHiddenId,
                startErrorId,
                endDisplayId,
                endHiddenId,
                endErrorId,
            } = config;

            const form = document.getElementById(formId);
            if (!form) return;

            const nameInput = document.getElementById(nameId);
            const nameError = document.getElementById(nameErrorId);
            const startDisplay = document.getElementById(startDisplayId);
            const startHidden = document.getElementById(startHiddenId);
            const startError = document.getElementById(startErrorId);
            const endDisplay = document.getElementById(endDisplayId);
            const endHidden = document.getElementById(endHiddenId);
            const endError = document.getElementById(endErrorId);

            if (!nameInput || !nameError || !startDisplay || !startHidden || !startError || !endDisplay || !endHidden || !endError) {
                return;
            }

            // converting to "RRRR-MM-DD" format
            function convertToISO(dateStr) {
                if (!dateStr || dateStr.trim() === '') return '';

                const parts = dateStr.split('/');
                if (parts.length !== 3) return '';

                const day = parts[0].padStart(2, '0');
                const month = parts[1].padStart(2, '0');
                const year = parts[2];

                return `${year}-${month}-${day}`;
            }

            // Validate date format and validity
            function validateDate(dateStr, isRequired = false) {
                if (!dateStr || dateStr.trim() === '') {
                    if (isRequired) {
                        return { valid: false, message: 'Toto pole je povinné.' };
                    }
                    return { valid: true, message: '' }; // Optional field - empty is OK
                }

                const parts = dateStr.split('/');
                if (parts.length !== 3) {
                    return { valid: false, message: 'Neplatný formát data. Použijte DD/MM/RRRR.' };
                }

                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10);
                const year = parseInt(parts[2], 10);

                if (isNaN(day) || isNaN(month) || isNaN(year)) {
                    return { valid: false, message: 'Datum musí obsahovat pouze čísla.' };
                }

                if (day < 1 || day > 31) {
                    return { valid: false, message: 'Den musí být mezi 1 a 31.' };
                }

                if (month < 1 || month > 12) {
                    return { valid: false, message: 'Měsíc musí být mezi 1 a 12.' };
                }

                if (year < 1900 || year > 2500) {
                    return { valid: false, message: 'Rok musí být mezi 1900 a 2500.' };
                }

                // Check if date is valid
                const date = new Date(year, month - 1, day);
                if (date.getFullYear() !== year ||
                    date.getMonth() !== month - 1 ||
                    date.getDate() !== day) {
                    return { valid: false, message: 'Neplatné datum (např. 30. února neexistuje).' };
                }

                return { valid: true, message: '' };
            }

            function showError(input, errorDiv, message) {
                input.classList.add('is-invalid');
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
            }

            function clearError(input, errorDiv) {
                input.classList.remove('is-invalid');
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';
            }

            function syncStartDate() {
                const isoDate = convertToISO(startDisplay.value);
                startHidden.value = isoDate;
            }

            function syncEndDate() {
                const isoDate = convertToISO(endDisplay.value);
                endHidden.value = isoDate;
            }

            startDisplay.addEventListener('blur', function() {
                syncStartDate();
                const validation = validateDate(this.value, true); // true = required
                if (!validation.valid) {
                    showError(this, startError, validation.message);
                } else {
                    clearError(this, startError);
                }
            });

            startDisplay.addEventListener('change', syncStartDate);

            endDisplay.addEventListener('blur', function() {
                syncEndDate();
                if (this.value.trim() !== '') {
                    const validation = validateDate(this.value);
                    if (!validation.valid) {
                        showError(this, endError, validation.message);
                    } else {
                        clearError(this, endError);
                    }
                } else {
                    clearError(this, endError);
                }
            });

            endDisplay.addEventListener('change', syncEndDate);

            // Name validation
            nameInput.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    showError(this, nameError, 'Toto pole je povinné.');
                } else {
                    clearError(this, nameError);
                }
            });

            form.addEventListener('submit', function(e) {
                syncStartDate();
                syncEndDate();

                // 1. Validate name (FIRST)
                if (nameInput.value.trim() === '') {
                    e.preventDefault();
                    showError(nameInput, nameError, 'Toto pole je povinné.');
                    nameInput.focus();
                    return;
                } else {
                    clearError(nameInput, nameError);
                }

                // 2. Validate start date (SECOND)
                const startValidation = validateDate(startDisplay.value, true);
                if (!startValidation.valid) {
                    e.preventDefault();
                    showError(startDisplay, startError, startValidation.message);
                    startDisplay.focus();
                    return;
                }

                // 3. Validate end date (THIRD)
                if (endDisplay.value.trim() !== '') {
                    const endValidation = validateDate(endDisplay.value);
                    if (!endValidation.valid) {
                        e.preventDefault();
                        showError(endDisplay, endError, endValidation.message);
                        endDisplay.focus();
                        return;
                    }
                }
            });

            // Input mask for date fields
            [startDisplay, endDisplay].forEach(input => {
                input.addEventListener('input', function() {
                    // Allow only numbers and slashes
                    this.value = this.value.replace(/[^\d\/]/g, '');

                    // Auto-formatting (adding slashes)
                    let val = this.value.replace(/\//g, '');
                    if (val.length >= 2) {
                        val = val.substring(0, 2) + '/' + val.substring(2);
                    }
                    if (val.length >= 5) {
                        val = val.substring(0, 5) + '/' + val.substring(5);
                    }
                    if (val.length > 10) {
                        val = val.substring(0, 10);
                    }
                    this.value = val;
                });
            });

            // Initialize Flatpickr for date fields (if available)
            if (typeof flatpickr !== 'undefined') {
                flatpickr(startDisplay, {
                    dateFormat: 'd/m/Y',
                    locale: 'cs',
                    allowInput: true, // Allow manual typing
                    minDate: '1900-01-01',
                    maxDate: '2500-12-31',
                    onChange: function(selectedDates, dateStr) {
                        syncStartDate();
                        // Trigger validation
                        const validation = validateDate(dateStr, true);
                        if (!validation.valid) {
                            showError(startDisplay, startError, validation.message);
                        } else {
                            clearError(startDisplay, startError);
                        }
                    }
                });

                flatpickr(endDisplay, {
                    dateFormat: 'd/m/Y',
                    locale: 'cs',
                    allowInput: true,
                    minDate: '1900-01-01',
                    maxDate: '2500-12-31',
                    onChange: function(selectedDates, dateStr) {
                        syncEndDate();
                        if (dateStr.trim() !== '') {
                            const validation = validateDate(dateStr);
                            if (!validation.valid) {
                                showError(endDisplay, endError, validation.message);
                            } else {
                                clearError(endDisplay, endError);
                            }
                        } else {
                            clearError(endDisplay, endError);
                        }
                    }
                });
            }
        };
    })();
</script>
