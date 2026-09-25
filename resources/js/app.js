import './bootstrap';

window.alert("Working");
console.log("JavaScript Working");

axios.get("/opt/", {
   params: {},
})
.then((response) => {
   console.log(response.data);
   // const api_data = document.getElementById("api_data");
   // api_data.innerHTML = response.data;
})
.catch((error) => {
   console.error(error);
})
.finally(() => {
   console.log("Request completed");
});