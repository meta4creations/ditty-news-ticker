const { withFilters, SlotFillProvider, Slot } = wp.components;

const SettingsComponent = (props) => {
  const AdditionalSettings = withFilters("myExamplePlugin.Settings")(
    (props) => <h1>Initial Filter</h1>
  );
  const exampleProp = "exampleProp";
  return (
    <SlotFillProvider>
      <AdditionalSettings exampleProp={exampleProp} {...props} />
      <div className="settings-container">
        <Slot name="SettingsTop" />
        <div className="setting-item">// Existing setting in main plugin</div>
        <div className="setting-item">// Existing setting in main plugin</div>
        <Slot name="SettingsBottom" />
      </div>
    </SlotFillProvider>
  );
};
export default SettingsComponent;
