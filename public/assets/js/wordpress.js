const WordPressAPI = {
  baseURL: "http://172.16.2.102:8000/wp-json/bcs/v1/settings",
  
  getSettings: async function() {
    try {
      const res = await fetch(this.baseURL);
      return await res.json();
    } catch (err) {
      console.log("API Error:", err);
      return null;
    }
  }
};