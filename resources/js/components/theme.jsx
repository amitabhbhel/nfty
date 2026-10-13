import React from "react";

export default function Theme() {
	const [theme, setTheme] = React.useState('light');
	const toggleTheme = () => {
		setTheme(theme === 'dark' ? 'light' : 'dark');
	};
	// initially set the theme and "listen" for changes to apply them to the HTML tag
	React.useEffect(() => {
		document.querySelector('html').setAttribute('data-theme', theme);
	}, [theme]);

   return (
      <div className="flex-none">
         <input type="checkbox" value="synthwave" class="toggle theme-controller" onClick={toggleTheme} />
      </div>
   )
}