<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PDF Viewer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        #pdf-container {
            height: 1000px;
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center">
    <button id="prev-button" class="btn btn-primary mr-2" disabled>Previous</button>
    <button id="next-button" class="btn btn-primary mr-2">Next</button>
    <button id="zoomin-button" class="btn btn-primary mr-2">Zoom In</button>
    <button id="zoomout-button" class="btn btn-primary mr-2">Zoom Out</button>
</div>
<div id="pdf-container" class="d-flex justify-content-center align-items-center"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/plugins/pdfjs/build/pdf.js') }}"></script>
<script>
    // Khởi tạo PDF.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.worker.min.js';
    pdfjsLib.GlobalWorkerOptions.workerSrc = '{{ asset('assets/plugins/pdfjs/build/pdf.worker.js') }}';

    // Đường dẫn tới file PDF
    const pdfUrl = '{{ asset($pdf_path) }}';

    let currentPage = 1;
    let zoomLevel = 1.0;

    // Tạo một đối tượng PDF
    let pdfDoc = null;

    const prevButton = document.getElementById('prev-button');
    const nextButton = document.getElementById('next-button');
    const zoomInButton = document.getElementById('zoomin-button');
    const zoomOutButton = document.getElementById('zoomout-button');
    const pdfContainer = document.getElementById('pdf-container');

    // Hiển thị trang PDF
    function renderPage(num) {
        pdfDoc.getPage(num).then(page => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const viewport = page.getViewport({scale: zoomLevel});
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            page.render(renderContext).promise.then(() => {
                pdfContainer.appendChild(canvas);
            });
        });
    }

    // Tải và hiển thị PDF
    pdfjsLib.getDocument(pdfUrl).promise.then(doc => {
        pdfDoc = doc;
        renderPage(currentPage);

        // Cập nhật trạng thái nút Previous và Next
        function updateButtonStates() {
            prevButton.disabled = (currentPage <= 1);
            nextButton.disabled = (currentPage >= pdfDoc.numPages);
        }

        // Sự kiện nút Previous
        prevButton.addEventListener('click', () => {
            if (currentPage <= 1) return;
            currentPage--;
            pdfContainer.innerHTML = '';
            renderPage(currentPage);
            updateButtonStates();
        });
        // Sự kiện nút next
        nextButton.addEventListener('click', () => {
            if (currentPage >= pdfDoc.numPages) return;
            currentPage++;
            pdfContainer.innerHTML = '';
            renderPage(currentPage);
            updateButtonStates();
        });
        // Sự kiện nút Zoom In
        zoomInButton.addEventListener('click', () => {
            zoomLevel += 0.1;
            pdfContainer.innerHTML = '';
            renderPage(currentPage);
        });

        // Sự kiện nút Zoom Out
        zoomOutButton.addEventListener('click', () => {
            if (zoomLevel <= 0.2) return;
            zoomLevel -= 0.1;
            pdfContainer.innerHTML = '';
            renderPage(currentPage);
        });

        // Cập nhật trạng thái ban đầu của các nút
        updateButtonStates();
    });
</script>
</body>
</html>
