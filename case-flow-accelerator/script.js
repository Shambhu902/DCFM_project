document.addEventListener('DOMContentLoaded', function() {
    // Sidebar navigation
    const navItems = document.querySelectorAll('nav ul li a');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            navItems.forEach(i => i.classList.remove('active-nav-item'));
            this.classList.add('active-nav-item');
            
            // Here you would load the appropriate content for the clicked nav item
            console.log(`Loading: ${this.textContent.trim()}`);
        });
    });

    // Case status filter (would be implemented in the case management view)
    const statusFilters = document.querySelectorAll('.status-filter');
    if (statusFilters) {
        statusFilters.forEach(filter => {
            filter.addEventListener('click', function() {
                statusFilters.forEach(f => f.classList.remove('active-filter'));
                this.classList.add('active-filter');
                // Filter cases based on status
            });
        });
    }

    // Modal for adding new case (would be implemented)
    const addCaseBtn = document.getElementById('add-case-btn');
    const caseModal = document.getElementById('case-modal');
    const closeModal = document.querySelector('.close-modal');
    
    if (addCaseBtn && caseModal && closeModal) {
        addCaseBtn.addEventListener('click', function() {
            caseModal.classList.remove('hidden');
            caseModal.classList.add('fade-in');
        });
        
        closeModal.addEventListener('click', function() {
            caseModal.classList.add('hidden');
        });
    }

    // Simulate loading data
    setTimeout(() => {
        const loadingElements = document.querySelectorAll('.loading-skeleton');
        loadingElements.forEach(el => el.classList.remove('loading-skeleton'));
    }, 1000);

    // Calendar navigation (simplified)
    const prevMonthBtn = document.querySelector('.calendar-prev');
    const nextMonthBtn = document.querySelector('.calendar-next');
    
    if (prevMonthBtn && nextMonthBtn) {
        prevMonthBtn.addEventListener('click', function() {
            console.log('Navigate to previous month');
        });
        
        nextMonthBtn.addEventListener('click', function() {
            console.log('Navigate to next month');
        });
    }

    // Responsive menu toggle (for mobile)
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('hidden');
        });
    }
});

// Case Management Functions
function addNewCase(caseData) {
    // This would be implemented to add a new case to the system
    console.log('Adding new case:', caseData);
    // Would typically make an API call here
}

function filterCases(filterCriteria) {
    // This would filter the displayed cases based on criteria
    console.log('Filtering cases by:', filterCriteria);
}

function updateCaseStatus(caseId, newStatus) {
    // This would update a case's status
    console.log(`Updating case ${caseId} to status: ${newStatus}`);
}

// Calendar Functions
function renderCalendar(month, year) {
    // This would render the calendar for the specified month/year
    console.log(`Rendering calendar for ${month}/${year}`);
}