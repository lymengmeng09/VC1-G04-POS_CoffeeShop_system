document.addEventListener('DOMContentLoaded', function () {
  console.log('DOM fully loaded'); // Debug: Check if the script runs

  // Get the dropdown element
  const userDropdown = document.getElementById('userDropdown');
  if (!userDropdown) {
      console.error('userDropdown element not found');
      return;
  }

  // Get the dropdown menu
  const dropdownMenu = userDropdown.nextElementSibling;
  if (!dropdownMenu) {
      console.error('Dropdown menu not found');
      return;
  }

  // Get dropdown items
  const dropdownItems = dropdownMenu.querySelectorAll('.dropdown-item');
  if (dropdownItems.length === 0) {
      console.error('No dropdown items found');
      return;
  }

  // Debug: Log the number of dropdown items found
  console.log('Found', dropdownItems.length, 'dropdown items');

  // Add click event listeners to dropdown items
  dropdownItems.forEach(item => {
      item.addEventListener('click', function (e) {
          const itemText = this.textContent.trim();
          console.log('Dropdown item clicked:', itemText); // Debug: Check if click event fires

          if (itemText === 'Profile') {
              console.log('Profile clicked!');
              // Add custom logic here, e.g., redirect to profile page
              // window.location.href = '/profile'; // Uncomment to redirect
          } else if (itemText === 'Logout') {
              console.log('Logout clicked!');
              // Add a confirmation dialog before logging out
              const confirmLogout = confirm('Are you sure you want to logout?');
              if (!confirmLogout) {
                  e.preventDefault(); // Prevent the default logout link behavior
                  console.log('Logout canceled');
              } else {
                  console.log('Proceeding with logout');
              }
          }
      });
  });
});