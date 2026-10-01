/// Main Content
import React from 'react';
import {createRoot} from 'react-dom/client';

export default function Block() {
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

			<div class="flex flex-row">
				<div class="basis-full">
					<div className="navbar bg-base-100 shadow-sm">
						<div className="flex-none">
							<button className="btn btn-square btn-ghost">
								<svg aria-label="Menu" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" className="inline-block h-5 w-5 stroke-current"> <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16"></path> </svg>
							</button>
						</div>
						<div className="flex-1">
							<a className="btn btn-ghost text-xl" href='/'>XYZ</a>
						</div>
						<div className="flex-none">
							<button className="btn btn-square btn-ghost">
								<svg aria-label="More" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" className="inline-block h-5 w-5 stroke-current"> <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path> </svg>
							</button>
						</div>
					</div>
					<main className="grid">
						<div className='col-span-full'>
							<h3>Home Page </h3>
						</div>
					</main>
				</div>
			</div>

			<div className="flex flex-col min-h-screen">
				<header className="bg-blue-600 text-white p-4">Header</header>

				<main className="flex-grow container mx-auto p-4">
					Content goes here...
				</main>

				<footer className="bg-gray-800 text-white p-4 text-center">Footer</footer>
			</div>

		</div>
	);
}

if (document.getElementById('app')) {
    const root = createRoot(document.getElementById('app'));
    root.render(<Block />);
}