/// Main Content
import React from 'react';
import {createRoot} from 'react-dom/client';

export default function Block() {
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

	return (
		<div>
			<h3>Home Page </h3>
		</div>
	);
}

if (document.getElementById('app')) {
    const root = createRoot(document.getElementById('app'));
    root.render(<Block />);
}