
<script>
    const container = document.getElementById('pdfContainer');
    const pdfUrl = '{{ asset($pdf_path) }}';
    let currentPage = 1;

    // View pdf
    function renderPage(page, canvas) {
        const viewport = page.getViewport({scale: 1});
        const canvasContext = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        page.render({canvasContext, viewport});
    }

    // Get pdf from prdUrl
    pdfjsLib.getDocument(pdfUrl).promise.then(function (pdf) {
        function loadPage() {
            pdf.getPage(currentPage).then(function (page) {

                // New div
                // w-full rounded-2lg py-12

                const newDiv = document.createElement('div');
                newDiv.classList.add('bg-white', 'rounded-2lg', 'lg:px-28', 'px-8', 'py-14');
                if (currentPage > 1) {
                    newDiv.classList.add('mt-4');
                }
                // New canvas
                const newCanvas = document.createElement('canvas');
                newCanvas.classList.add('w-full');

                // Add div to pdfContainer
                newDiv.appendChild(newCanvas);
                container.appendChild(newDiv);

                // Render pdf in new page
                renderPage(page, newCanvas);

                if (currentPage < pdf.numPages) {
                    currentPage++;
                    document.getElementById('loadMoreButton').style.display = 'block';
                } else {
                    document.getElementById('loadMoreButton').style.display = 'none';
                }
            });
        }

        document.getElementById('loadMoreButton').addEventListener('click', function () {
            loadPage();
        });

        loadPage();
    });

</script>
