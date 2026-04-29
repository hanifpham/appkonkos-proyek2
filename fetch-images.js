const cities = ['Yogyakarta', 'Jakarta', 'Bandung', 'Surabaya', 'Malang', 'Semarang', 'Medan', 'Cirebon', 'Indramayu', 'Depok', 'Bogor'];
async function fetchImages() {
  for (const city of cities) {
    const res = await fetch(`https://id.wikipedia.org/w/api.php?action=query&titles=${city}&prop=pageimages&format=json&pithumbsize=600`);
    const data = await res.json();
    const pages = data.query.pages;
    const pageId = Object.keys(pages)[0];
    if (pages[pageId].thumbnail) {
      console.log(city + '|' + pages[pageId].thumbnail.source);
    } else {
      console.log(city + '|NO IMAGE');
    }
  }
}
fetchImages();
