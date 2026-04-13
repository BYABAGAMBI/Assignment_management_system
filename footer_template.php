</div>
    </main>

    <script>
        let sidebarTimeout;
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const triggerZone = document.querySelector('.sidebar-trigger-zone');
        
        // Toggle sidebar function
        function toggleSidebar() {
            sidebar.classList.toggle('show');
            mainContent.classList.toggle('expanded');
        }
        
        // Show sidebar when cursor enters trigger zone
        triggerZone.addEventListener('mouseenter', function() {
            clearTimeout(sidebarTimeout);
            sidebar.classList.add('show');
            mainContent.classList.add('expanded');
        });
        
        // Hide sidebar when cursor leaves trigger zone and sidebar
        function hideSidebar() {
            sidebarTimeout = setTimeout(function() {
                sidebar.classList.remove('show');
                mainContent.classList.remove('expanded');
            }, 300);
        }
        
        triggerZone.addEventListener('mouseleave', hideSidebar);
        
        sidebar.addEventListener('mouseleave', function(e) {
            // Only hide if mouse is not entering trigger zone
            if (!e.relatedTarget || !e.relatedTarget.closest('.sidebar-trigger-zone')) {
                hideSidebar();
            }
        });
        
        // Prevent hiding when moving from trigger zone to sidebar
        sidebar.addEventListener('mouseenter', function() {
            clearTimeout(sidebarTimeout);
        });
        
        // Touch support for mobile
        triggerZone.addEventListener('touchstart', function() {
            sidebar.classList.add('show');
            mainContent.classList.add('expanded');
        });
        
        // Hide on touch outside
        document.addEventListener('touchstart', function(e) {
            if (!sidebar.contains(e.target) && !triggerZone.contains(e.target)) {
                sidebar.classList.remove('show');
                mainContent.classList.remove('expanded');
            }
        });
    </script>
</body>
</html>
