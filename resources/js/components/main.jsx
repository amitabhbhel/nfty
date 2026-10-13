/// Main Content
import React from 'react';
import { createRoot } from 'react-dom/client';
import Theme from './theme';
import Table from './table';
import Form from './form';

// Shared Logic Hook
function useFetchData(url) {
	const [data, setData] = React.useState(null);

	React.useEffect(() => {
		// fetch(url).then(res => res.json()).then(setData);
		axios.get(url, {
			params: {},
		})
			.then((response) => {
				setData(response.data);
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
	}, [url]);

	return data;
}

export default function Block() {
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
						<Theme></Theme>
					</div>
				</div>
			</div>

			<main class="flex-1 overflow-y-auto p-2">
				<div class="max-w-none">
					<Form></Form>
				</div>
				<div class="max-w-none">
					<h1 class="mb-4 text-3xl font-bold">Full Width Viewport Content</h1>
					<p>This layout stretches completely across the screen width and fills the full viewport height.</p>
				</div>
				<Table></Table>
			</main>

			<footer class="footer w-full shrink-0 footer-center bg-base-300 p-4 text-base-content sm:footer-horizontal">
				<aside>
					<p>Copyright © 2026 - All right reserved</p>
				</aside>
			</footer>

		</div>
	);
}

if (document.getElementById('app')) {
	const root = createRoot(document.getElementById('app'));
	root.render(<Block />);
}