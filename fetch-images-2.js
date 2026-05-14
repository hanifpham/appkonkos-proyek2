const https = require('https');
const cities = ['Yogyakarta', 'Jakarta', 'Surabaya', 'Semarang', 'Cirebon', 'Depok'];

cities.forEach((city, index) => {
  setTimeout(() => {
    https.get(`https://id.wikipedia.org/api/rest_v1/page/summary/${city}`, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        try {
          const json = JSON.parse(data);
          if (json.thumbnail) {
            console.log(`${city}: ${json.thumbnail.source}`);
          } else {
            console.log(`${city}: NO IMAGE`);
          }
        } catch (e) {
          console.log(`${city}: ERROR ${e.message}`);
        }
      });
    });
  }, index * 1000); // 1 sec delay to avoid rate limit
});
