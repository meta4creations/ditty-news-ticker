export default class DittyItem {
	constructor(config) {
		const defaults = {
			data: {},
			layout: null,
			type: null,
		};
		this.config = { ...defaults, ...config };
	}

	render() {}
}
