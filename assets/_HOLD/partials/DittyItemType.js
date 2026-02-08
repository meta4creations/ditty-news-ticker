export default class DittyItemType {
  constructor(config) {
    const defaults = {
      id: "",
      icon: "",
      label: "",
      description: "",
      settings: {},
      defaultValues: {},
    };
    this.config = { ...defaults, ...config };
  }
}
