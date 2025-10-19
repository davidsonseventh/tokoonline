</div> </div> </div> <script>
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelector = document.getElementById('mdmv_role');
        const storeNameField = document.getElementById('store_name_field');

        if (roleSelector) {
            function toggleStoreField() {
                if (roleSelector.value === 'vendor') {
                    storeNameField.style.display = 'block';
                    storeNameField.querySelector('input').required = true;
                } else {
                    storeNameField.style.display = 'none';
                    storeNameField.querySelector('input').required = false;
                }
            }
            roleSelector.addEventListener('change', toggleStoreField);
            // Panggil saat load
            toggleStoreField(); 
        }
    });
    </script>
</body>
</html>