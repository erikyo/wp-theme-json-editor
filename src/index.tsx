import './style.css';
import schemaJson from './schema.json'

window.addEventListener( 'DOMContentLoaded', async ( event ) => {

	const rootEl = document.getElementById('root');
	const dataset = document.getElementById('json-dataset');
	const content = dataset.innerText as string
	const schema =  rootEl.dataset.schema
	const theme =  rootEl.dataset.theme || 'spectre'
	const icon   =  rootEl.dataset.theme || 'materialize'


	/**
	 * Update the value and validation state.
	 *
	 * @param {any} value - the new value to be set
	 * @param {boolean} isValid - the new validation state
	 */
	const updateValue = (value: string, isValid: boolean) => {
		dataset.innerHTML = value
	};

	const newValue = JSON.parse(content)
	const currentSchema = schemaJson
		? schemaJson
		: await fetch(schema, {
			mode: 'no-cors',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
				'Access-Control-Allow-Origin': '*',
				'Access-Control-Allow-Headers': 'Content-Type, Authorization, X-Requested-With',
				'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS'
			}
		} ).then( response => response.json()  );

	const options = Object.assign( {}, JSONEditor.defaults.options, {
		icon: icon,
		theme: 'spectre',
		schema: currentSchema,
		show_opt_in: true,

		disable_edit_json: true,
		disable_properties: true,
		disable_collapse: true,
		max_depth: 3,
		updateValue: updateValue
	});

	// new instance of JSONEditor
	const editor = new JSONEditor(  rootEl, options );


})