// edit.js
import { __ } from "@wordpress/i18n";
import {
  MediaUpload,
  MediaUploadCheck,
  PanelColorSettings,
  __experimentalSpacingSizesControl as SpacingSizesControl,
} from "@wordpress/block-editor";
import {
  BorderControl,
  Button,
  PanelBody,
  RangeControl,
  SelectControl,
  TabPanel,
  ToggleControl,
  __experimentalUnitControl as UnitControl,
  __experimentalUseCustomUnits as useCustomUnits,
} from "@wordpress/components";

const units = useCustomUnits({
  availableUnits: ["px", "rem", "%", "vw", "vh"],
});

export default function ArrowStyles(props) {
  const { attributes, setAttributes } = props;

  const tabs = [
    { name: "normal", title: __("Default", "ditty-pro") },
    { name: "hover", title: __("Hover", "ditty-pro") },
  ];

  return (
    attributes.arrows && (
      <>
        <PanelBody
          title={__("Arrow Navigation Container", "ditty-pro")}
          initialOpen={false}
        >
          <SelectControl
            label={__("Arrows Position", "ditty-pro")}
            help={__("Vertical position of the arrows", "ditty-pro")}
            value={attributes.arrowsPosition}
            options={[
              { label: __("Top", "ditty-pro"), value: "flexStart" },
              { label: __("Center", "ditty-pro"), value: "center" },
              { label: __("Bottom", "ditty-pro"), value: "flexEnd" },
            ]}
            onChange={(val) => setAttributes({ arrowsPosition: val })}
          />
          <SpacingSizesControl
            label={__("Arrows Padding", "ditty-pro")}
            values={attributes.arrowsPadding}
            onChange={(val) => setAttributes({ arrowsPadding: val })}
            units={["px", "em", "rem"]}
          />
          <ToggleControl
            label={__("Keep arrows visible at all times", "ditty-pro")}
            help={__("Show arrows even when not hovering", "ditty-pro")}
            checked={attributes.arrowsStatic ? true : false}
            onChange={(val) => setAttributes({ arrowsStatic: val })}
          />
        </PanelBody>
        <PanelBody
          title={__("Arrow Navigation Buttons", "ditty-pro")}
          initialOpen={false}
        >
          {/* Prev Icon */}
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ arrowPrevIcon: media.id })}
              allowedTypes={["image/png", "image/svg+xml"]}
              value={attributes.arrowPrevIcon}
              render={({ open }) =>
                attributes.arrowPrevIcon ? (
                  <div className="arrow-icon-upload">
                    <Button onClick={open}>
                      {__("Change Prev Icon", "ditty-pro")}
                    </Button>
                    <Button
                      isDestructive
                      onClick={() => setAttributes({ arrowPrevIcon: 0 })}
                    >
                      {__("Remove Icon", "ditty-pro")}
                    </Button>
                  </div>
                ) : (
                  <Button variant="primary" onClick={open}>
                    {__("Upload Prev Icon", "ditty-pro")}
                  </Button>
                )
              }
            />
          </MediaUploadCheck>

          {/* Next Icon */}
          <MediaUploadCheck>
            <MediaUpload
              onSelect={(media) => setAttributes({ arrowNextIcon: media.id })}
              allowedTypes={["image/png", "image/svg+xml"]}
              value={attributes.arrowNextIcon}
              render={({ open }) =>
                attributes.arrowNextIcon ? (
                  <div className="arrow-icon-upload">
                    <Button onClick={open}>
                      {__("Change Next Icon", "ditty-pro")}
                    </Button>
                    <Button
                      isDestructive
                      onClick={() => setAttributes({ arrowNextIcon: 0 })}
                    >
                      {__("Remove Icon", "ditty-pro")}
                    </Button>
                  </div>
                ) : (
                  <Button variant="primary" onClick={open}>
                    {__("Upload Next Icon", "ditty-pro")}
                  </Button>
                )
              }
            />
          </MediaUploadCheck>

          {/* <RangeControl
            label={__("Arrow Width (px)", "ditty")}
            value={attributes.arrowWidth}
            onChange={(val) => setAttributes({ arrowWidth: val })}
            min={0}
            max={300}
          />
          <RangeControl
            label={__("Arrow Height (px)", "ditty")}
            value={attributes.arrowHeight}
            onChange={(val) => setAttributes({ arrowHeight: val })}
            min={0}
            max={300}
          /> */}
          <RangeControl
            label={__("Icon Width (px)", "ditty")}
            value={attributes.arrowIconWidth}
            onChange={(val) => setAttributes({ arrowIconWidth: val })}
            min={0}
            max={300}
          />
          <UnitControl
            label={__("Border Radius", "ditty")}
            value={attributes.arrowBorderRadius}
            onChange={(value) => {
              console.log("arrowBorderRadius", value);
              setAttributes({ arrowBorderRadius: value });
            }}
            units={units}
          />

          <TabPanel className="ditty-arrow-color-tabs" tabs={tabs}>
            {(tab) => {
              const isHover = tab.name === "hover";
              return (
                <PanelColorSettings
                  title={
                    isHover
                      ? __("Hover Colors", "ditty-pro")
                      : __("Colors", "ditty-pro")
                  }
                  initialOpen={true}
                  colorSettings={[
                    {
                      label: __("Icon Color", "ditty-pro"),
                      value: isHover
                        ? attributes.arrowIconHoverColor
                        : attributes.arrowIconColor,
                      onChange: (color) => {
                        const key = isHover
                          ? "arrowIconHoverColor"
                          : "arrowIconColor";
                        setAttributes({ [key]: color });
                      },
                      enableAlpha: true,
                    },
                    {
                      label: __("BG Color", "ditty-pro"),
                      value: isHover
                        ? attributes.arrowBgHoverColor
                        : attributes.arrowBgColor,
                      onChange: (color) => {
                        const key = isHover
                          ? "arrowBgHoverColor"
                          : "arrowBgColor";
                        setAttributes({ [key]: color });
                      },
                      enableAlpha: true,
                    },
                  ]}
                />
              );
            }}
          </TabPanel>
        </PanelBody>
      </>
    )
  );
}
