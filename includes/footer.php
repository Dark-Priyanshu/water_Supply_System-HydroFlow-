        </div> <!-- End Page Content Wrapper -->
    </main> <!-- End Main Content Wrapper -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom Script -->
    <script>
        // Mobile Sidebar Toggle Logic
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebarMenu');
            const overlay = document.getElementById('mobileOverlay');
            sidebar.classList.toggle('-translate-x-full');
            if (overlay.classList.contains('hidden')) {
                overlay.classList.remove('hidden');
                setTimeout(() => overlay.classList.remove('opacity-0'), 10);
            } else {
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        };

        $(document).ready(function() {
            // Initialize datatables
            if ($('.datatable').length) {
                $('.datatable').DataTable({
                    "pageLength": 10,
                    "ordering": true,
                    "info": true,
                    "dom": "<'flex flex-col md:flex-row justify-between items-center mb-4'lf>" +
                           "<'overflow-x-auto w-full'tr>" +
                           "<'flex flex-col md:flex-row justify-between items-center mt-4 gap-4'ip>",
                    "language": {
                        "search": "_INPUT_",
                        "searchPlaceholder": "Search records...",
                        "emptyTable": `<div class="py-12 text-center flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-outline/30 mb-4 block">folder_off</span>
                            <p class="text-[15px] font-semibold text-on-surface-variant">No records available.</p>
                            <p class="text-xs text-outline mt-1">Try adjusting your filters or adding new data.</p>
                        </div>`,
                        "zeroRecords": `<div class="py-12 text-center flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-5xl text-outline/30 mb-4 block">search_off</span>
                            <p class="text-[15px] font-semibold text-on-surface-variant">No matching records found.</p>
                            <p class="text-xs text-outline mt-1">Please try a different search query.</p>
                        </div>`
                    }
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
                            $(this).removeClass('hidden');
                        } else {
                            $(this).addClass('hidden');
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
