/**
 * Check field visibility based on other field values
 * @param {object} field
 * @returns
 */
export const showField = (field, values) => {
  if (!field.show) {
    return true;
  }

  let formattedValues = Array.isArray(values) ? {} : values;
  if (Array.isArray(values)) {
    values.map((val) => {
      if (val.id) {
        formattedValues[val.id] = val.value ? val.value : "";
      }
    });
  }

  const operators = {
    "=": (a, b) => {
      return a === b;
    },
    "!=": (a, b) => {
      return a !== b;
    },
  };

  const relation = field.show.relation ? field.show.relation : "AND";
  const checks = field.show.fields.map((f) => {
    if (operators[f.compare](formattedValues[f.key], f.value)) {
      return "pass";
    } else {
      return "fail";
    }
  });

  if ("OR" === relation) {
    return checks.includes("pass");
  } else {
    return checks.every((v) => v === "pass");
  }
};
