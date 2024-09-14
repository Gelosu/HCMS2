import React, { useState } from 'react';

function YourComponent() {
  const [showAlert, setShowAlert] = useState(false);

  // Function to handle form submission
  const handleSubmit = async (event) => {
    event.preventDefault();
    const formData = new FormData(event.target);
    // Your form submission logic here
    const response = await fetch('patientprocess.php', {
      method: 'POST',
      body: formData
    });
    const data = await response.json(); // Parse JSON response
    if (!data.success && data.error === 'duplicate_entry') {
      setShowAlert(true); // Set showAlert to true to display the alert
    }
  };

  return (
    <div>
      {/* Conditionally render the alert */}
      {showAlert && (
        <div className="alert">
          Duplicate entry for primary key.
        </div>
      )}
      {/* Your form here */}
      <form onSubmit={handleSubmit}>
        {/* Form fields */}
        <input type="text" name="p_id" />
        {/* Other form fields */}
        <button type="submit">Submit</button>
      </form>
    </div>
  );
}

export default YourComponent;
