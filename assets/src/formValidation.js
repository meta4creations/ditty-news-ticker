// npm install formik yup
// See corrigan jobs

import React from "react";
import { useFormik } from "formik";
import * as Yup from "yup";

const validationSchema = Yup.object().shape({
  name: Yup.string().required("Name is required"),
  email: Yup.string().email("Invalid email").required("Email is required"),
  message: Yup.string().required("Message is required"),
});

const ContactForm = () => {
  const formik = useFormik({
    initialValues: { name: "", email: "", message: "" },
    validationSchema,
    onSubmit: (values) => {
      // Process the form data, e.g., submit to the WordPress backend
      if (window.console) {
        console.log(values);
      }
    },
  });

  const handleButtonClick = () => {
    formik.submitForm();
  };

  return (
    <>
      <label htmlFor="name">Name:</label>
      <input
        type="text"
        name="name"
        id="name"
        value={formik.values.name}
        onChange={formik.handleChange}
        onBlur={formik.handleBlur}
      />
      {formik.touched.name && formik.errors.name ? (
        <div>{formik.errors.name}</div>
      ) : null}

      <label htmlFor="email">Email:</label>
      <input
        type="email"
        name="email"
        id="email"
        value={formik.values.email}
        onChange={formik.handleChange}
        onBlur={formik.handleBlur}
      />
      {formik.touched.email && formik.errors.email ? (
        <div>{formik.errors.email}</div>
      ) : null}

      <label htmlFor="message">Message:</label>
      <textarea
        name="message"
        id="message"
        value={formik.values.message}
        onChange={formik.handleChange}
        onBlur={formik.handleBlur}
      />
      {formik.touched.message && formik.errors.message ? (
        <div>{formik.errors.message}</div>
      ) : null}

      <button
        type="button"
        onClick={handleButtonClick}
        disabled={!formik.isValid || formik.isSubmitting}
      >
        Submit
      </button>
    </>
  );
};

export default ContactForm;
