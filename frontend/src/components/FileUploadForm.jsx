import React, { useState } from 'react';
import axios from 'axios';

const FileUploadForm = () => {
  const [file, setFile] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleFileChange = (event) => {
    setFile(event.target.files[0]);
  };

  const handleSubmit = async (event) => {
    event.preventDefault();
    if (file) {
      const formData = new FormData();
      formData.append('file', file);

      try {
        setLoading(true);
        setError(null);

        const response = await axios.post('http://localhost:8000/api/upload', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
          responseType: 'blob',
        });

        const url = window.URL.createObjectURL(new Blob([response.data]));

        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'converted.docx');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        setLoading(false);
      } catch (error) {
        console.error(error);
        setError('An error occurred while uploading the file.');
        setLoading(false);
      }
    }
  };

  return (
    <div>
      <h2>PDF to Word Converter</h2>
      <form onSubmit={handleSubmit}>
        <input type="file" accept=".pdf" onChange={handleFileChange} />
        <button type="submit" disabled={!file || loading}>
          {loading ? 'Uploading...' : 'Convert and Download'}
        </button>
        {error && <div>{error}</div>}
      </form>
    </div>
  );
};

export default FileUploadForm;
