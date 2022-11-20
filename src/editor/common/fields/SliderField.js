import { __ } from "@wordpress/i18n";
import IonRangeSlider from "react-ion-slider";

const SliderField = ({ type, min, max, from, to, step, values, keyboard }) => {
  return (
    <IonRangeSlider
      type={type}
      min={min}
      max={max}
      from={from}
      to={to}
      step={step}
      values={values}
      keyboard={keyboard}
      onUpdate={(test) => {
        console.log(test);
      }}
    />
  );
};

export default SliderField;
