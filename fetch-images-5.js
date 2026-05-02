const companies = ['Pertamina', 'Telkom_Indonesia', 'Bank_Central_Asia'];

async function run() {
  for (const item of companies) {
    try {
      const res = await fetch(`https://id.wikipedia.org/api/rest_v1/page/summary/${item}`);
      const json = await res.json();
      if (json.thumbnail) {
        console.log(`${item}: ${json.thumbnail.source}`);
      } else {
        console.log(`${item}: NO IMAGE`);
      }
    } catch (e) {}
  }
}
run();
