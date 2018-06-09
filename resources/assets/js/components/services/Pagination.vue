<script>
    export default {
        pagination(current_page, total_pages) {
            let pages = [];
            const dots = '...';
            pages.push(`${current_page}`);
            for (var i = 1; i < 3; i++) {
                if (current_page - i > 0)
                    pages.unshift(`${current_page - i}`);
                if (current_page + i < total_pages)
                    pages.push(`${current_page + i}`);
            }
            if (current_page >= 5) {
                pages.unshift(dots);
            }
            if (current_page >= 4) {
                pages.unshift(`${1}`);
            }
            if (current_page <= total_pages - 5) {
                pages.push(dots);
            }
            if (current_page <= total_pages - 1) {
                pages.push(`${total_pages}`);
            }
            return pages;
        }
    }

</script>


