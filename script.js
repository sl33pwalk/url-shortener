async function shortenUrl() {
    const url = document.getElementById('url').value;
    const response = await fetch('shorten.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ url })
    });
    const data = await response.json();
    if (data.short_url) {
        const shortUrlElement = document.getElementById('short_url');
        shortUrlElement.href = data.short_url;
        shortUrlElement.textContent = data.short_url;
    } else {
        alert('Возникла ошибка при сокращении URL');
    }
}