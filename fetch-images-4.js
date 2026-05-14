const cities = ['Yogyakarta', 'Jakarta', 'Surabaya', 'Semarang', 'Cirebon', 'Depok', 'Bandung', 'Malang', 'Medan', 'Indramayu', 'Bogor'];
const unis = [
  'Universitas_Gadjah_Mada', 'Universitas_Diponegoro', 'Universitas_Indonesia',
  'Universitas_Padjadjaran', 'Politeknik_Keuangan_Negara_STAN', 'Universitas_Brawijaya',
  'Universitas_Airlangga', 'Politeknik_Negeri_Indramayu', 'Institut_Teknologi_Bandung',
  'Institut_Pertanian_Bogor', 'Institut_Teknologi_Sepuluh_Nopember'
];

async function run() {
  console.log("--- CITIES ---");
  for (const item of cities) {
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
  console.log("--- UNIS ---");
  for (const item of unis) {
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
