// Custom dropdown JS for notification and user menu (no Bootstrap dependency)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.custom-dropdown-toggle').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            var parent = btn.closest('.custom-dropdown');
            document.querySelectorAll('.custom-dropdown').forEach(function(drop) {
                if (drop !== parent) drop.classList.remove('open');
            });
            parent.classList.toggle('open');
        });
    });
    document.addEventListener('click', function() {
        document.querySelectorAll('.custom-dropdown').forEach(function(drop) {
            drop.classList.remove('open');
        });
    });
});
