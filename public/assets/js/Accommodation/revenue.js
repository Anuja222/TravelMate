function downloadPDF() {
    const element = document.querySelector('.content');
    const filterContainer = document.querySelector('.filter-container');
    const pdfHeader = document.getElementById('pdfHeader');
    const sidebar = document.querySelector('.sidebar');
    
    // Temporarily edit UI elements for the PDF
    if(filterContainer) filterContainer.style.display = 'none';
    if(pdfHeader) pdfHeader.style.display = 'block';
    
    const opt = {
        margin:       [0.5, 0.5, 0.5, 0.5],
        filename:     'TravelMate_Revenue_Report.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2, useCORS: true },
        jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' },
        pagebreak:    { mode: ['css', 'legacy'] }
    };

    // Generate PDF and then restore hidden elements
    html2pdf().set(opt).from(element).save().then(() => {
        if(filterContainer) filterContainer.style.display = 'flex';
        if(pdfHeader) pdfHeader.style.display = 'none';
    });
}