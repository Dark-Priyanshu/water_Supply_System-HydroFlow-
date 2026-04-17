        </div> <!-- End Page Content Wrapper -->
    </main> <!-- End Main Content Wrapper -->

    <!-- jQuery -->
    <script src="<?= BASE_URL ?>assets/vendor/jquery.min.js"></script>
    <!-- DataTables -->
    <script src="<?= BASE_URL ?>assets/vendor/jquery.dataTables.min.js"></script>
    <!-- Chart.js -->
    <script src="<?= BASE_URL ?>assets/vendor/chart.min.js"></script>
    <!-- Custom Script -->
    <script>
        // Mobile Sidebar Toggle Logic
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('mobileOverlay');
            
            sidebar.classList.toggle('sidebar-open');
            
            if (overlay.style.display === 'none' || overlay.style.display === '') {
                overlay.style.display = 'block';
                setTimeout(() => overlay.style.opacity = '1', 10);
            } else {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.style.display = 'none', 300);
            }
        };

        $(document).ready(function() {
            // Initialize datatables
            if ($('.datatable').length) {
                $('.datatable').DataTable({
                    "pageLength": 10,
                    "ordering": true,
                    "order": [], // Prevents default sort on first column, respects PHP ordering
                    "info": true,
                    "dom": "<'dt-container-row'lf>" +
                           "<'table-container'tr>" +
                           "<'dt-container-row'ip>",
                    "language": dtLanguage
                });
            }
            // Bind top global search to DataTables and generic cards
            $('#topGlobalSearch').on('keyup', function() {
                var searchVal = $(this).val().toLowerCase();
                
                // 1. Filter DataTables
                if ($('.datatable').length) {
                    $('.datatable').DataTable().search(searchVal).draw();
                }
                
                // 2. Filter Custom Grids / Cards
                if ($('.searchable-item').length) {
                    $('.searchable-item').each(function() {
                        var textContent = $(this).text().toLowerCase();
                        if (textContent.indexOf(searchVal) > -1) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
