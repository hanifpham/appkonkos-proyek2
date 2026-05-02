const cities = ['Bandung', 'Malang', 'Medan', 'Indramayu', 'Bogor'];

for (const city of cities) {
  try {
    const res = await fetch(`https://id.wikipedia.org/api/rest_v1/page/summary/${city}`);
    const json = await res.json();
    if (json.thumbnail) {
      console.log(`${city}: ${json.thumbnail.source}`);
    } else {
      console.log(`${city}: NO IMAGE`);
    }
  } catch (e) {
    console.log(`${city}: ERROR ${e.message}`);
  }
  await new Promise(r => setTimeout(r, 1000));
}
