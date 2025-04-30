
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Submit Report - WhistleSafe</title>
  <style>
    body {
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      transition: background-image 1s ease-in-out;
    }
    .bg-overlay {
      background-color: rgba(219, 234, 254, 0.85);
    }
    .submitMsg {
      background-color: #d1fae5;
      color: #065f46;
      padding: 1rem;
      margin: 1rem auto;
      max-width: 2xl;
      border-radius: 0.375rem;
      text-align: center;
    }
    .errorMsg {
      background-color: #fee2e2;
      color: #b91c1c;
      padding: 1rem;
      margin: 1rem auto;
      max-width: 2xl;
      border-radius: 0.375rem;
      text-align: center;
    }
    .tracking-id {
      font-weight: bold;
      color: #1e40af;
    }
    .loading {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255,255,255,.3);
      border-radius: 50%;
      border-top-color: #fff;
      animation: spin 1s ease-in-out infinite;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body class="font-sans">
  <!-- Background image changer -->
  <div class="fixed inset-0 bg-overlay z-0"></div>
  
  <div class="relative z-10">
    <header class="bg-white shadow p-4 flex justify-between items-center">
      <a href="index.html" class="text-xl font-bold text-blue-700">WhistleSafe</a>
      <nav>
        
        <a href="index.html" class="text-blue-700 mx-2">Home </a> 
        <a href="submit.php" class="text-blue-700 mx-2">Submit Report</a>
        <a href="track.html" class="text-blue-700 mx-2">Track Status</a>
        
           </nav>
    </header>

    <div id="successMessage" class="hidden submitMsg mx-4 max-w-2xl mx-auto"></div>
    <div id="errorMessage" class="hidden errorMsg mx-4 max-w-2xl mx-auto"></div>

    <section class="bg-white mt-6 p-6 rounded shadow mx-4 max-w-2xl mx-auto">
      <h3 class="text-2xl font-bold mb-4 text-blue-800">Submit Report</h3>
      <form id="reportForm" class="space-y-4">
        <div>
          <label class="block mb-1 text-gray-700">Category</label>
          <select id="category" class="w-full border p-2 rounded" required>
            <option value="">Select a category</option>
            <option>Harassment</option>
            <option>Safety</option>
            <option>Ragging</option>
            <option>Discrimination</option>
            <option>Academic Misconduct</option>
          </select>
        </div>
        <div>
          <label class="block mb-1 text-gray-700">Description</label>
          <textarea id="description" class="w-full border p-2 rounded" rows="3" required></textarea>
        </div>
        <div>
          <label class="block mb-1 text-gray-700">Location</label>
          <select id="location" class="w-full border p-2 rounded" required>
            <option value="">Select a location</option>
            <option>Building A</option>
            <option>Building C</option>
            <option>Main Entrance</option>
            <option>Dormitory</option>
            <option>Cafeteria</option>
            <option>Library</option>
          </select>
        </div>
        <div>
          <label class="block mb-1 text-gray-700">Evidence</label>
          <input id="evidence" type="file_text" class="w-full border p-2 rounded"  required>
          <p class="text-sm text-gray-500">You Can save your Evidence</p>
        </div>
        <div>
          <label class="block mb-1 text-gray-700">Your Email </label>
          <input id="email" type="email" class="w-full border p-2 rounded" required>
          <P class="text-sm text-blue-500">Provide Correct Email</P>
          <p class="text-sm text-gray-500">Provide if you want updates on your report</p>
        </div>
        <button type="submit" id="submitButton" class="bg-blue-700 text-white px-6 py-2 rounded hover:bg-blue-800 transition flex items-center justify-center min-w-32">
          <span id="buttonText">Submit</span>
          <span id="loadingSpinner" class="hidden loading ml-2"></span>
        </button>
      </form>
    </section>
  </div>

  <script>
    // Background image changer
    const images = [
      'url("https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1471&q=80")',
      'url("https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80")',
      'url("https://images.unsplash.com/photo-1521791055366-0d553872125f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1469&q=80")',
      'url("https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1422&q=80")'
    ];
    
    let currentImage = 0;
    
    function changeBackground() {
      document.body.style.backgroundImage = images[currentImage];
      currentImage = (currentImage + 1) % images.length;
    }
    
    changeBackground();
    setInterval(changeBackground, 8000);

    // Improved Tracking ID Generator
    function generateTrackingId() {
      const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
      let randomPart = '';
      for (let i = 0; i < 5; i++) {
        randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
      }
      const timestamp = Date.now().toString(36).toUpperCase(); // Base-36 encoded timestamp
      return `WS-${randomPart}-${timestamp}`;
    }

    // Simulated database API (using JSONBin.io as an example)
    const API_KEY = '$2b$10$YOUR_API_KEY'; // Replace with your actual API key
    const BIN_ID = 'YOUR_BIN_ID'; // Replace with your actual bin ID
    const DB_URL = `https://api.jsonbin.io/v3/b/${BIN_ID}`;

    async function saveToDatabase(report) {
      try {
        // In a real app, you would send this to your backend API
        // For demo purposes, we'll simulate this with JSONBin.io
        
        // Get existing reports
        const response = await fetch(DB_URL + '/latest', {
          headers: {
            'X-Master-Key': API_KEY,
            'Content-Type': 'application/json'
          }
        });
        
        if (!response.ok) throw new Error('Failed to fetch existing reports');
        
        const data = await response.json();
        const existingReports = data.record?.reports || [];
        
        // Add new report
        const updatedReports = [...existingReports, report];
        
        // Update the database
        const updateResponse = await fetch(DB_URL, {
          method: 'PUT',
          headers: {
            'X-Master-Key': API_KEY,
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ reports: updatedReports })
        });
        
        if (!updateResponse.ok) throw new Error('Failed to update database');
        
        return true;
      } catch (error) {
        console.error('Database error:', error);
        return false;
      }
    }

    // Form submission
    document.getElementById('reportForm').addEventListener('submit', async function(e) {
      e.preventDefault();

      const submitButton = document.getElementById('submitButton');
      const buttonText = document.getElementById('buttonText');
      const loadingSpinner = document.getElementById('loadingSpinner');
      
      // Show loading state
      submitButton.disabled = true;
      buttonText.textContent = 'Processing...';
      loadingSpinner.classList.remove('hidden');

      const trackingId = generateTrackingId();
      
      const report = {
        trackingId: trackingId,
        category: document.getElementById('category').value.trim(),
        description: document.getElementById('description').value.trim(),
        location: document.getElementById('location').value.trim(),
        evidence: document.getElementById('evidence').files.length > 0 
          ? Array.from(document.getElementById('evidence').files).map(f => f.name).join(', ')
          : 'None',
        email: document.getElementById('email').value.trim(),
        date: new Date().toLocaleString(),
        status: 'Submitted',
        timestamp: new Date().toISOString()
      };

      try {
        // Save to localStorage (client-side)
        const reports = JSON.parse(localStorage.getItem('whistlesafe_reports')) || [];
        reports.push(report);
        localStorage.setItem('whistlesafe_reports', JSON.stringify(reports));
        
        // Save to database (server-side)
        const dbSuccess = await saveToDatabase(report);
        
        if (!dbSuccess) {
          // If database save fails, we still have it in localStorage
          console.warn('Report saved locally but failed to save to database');
        }
        
        // Show success message
        const successMessage = document.getElementById('successMessage');
        successMessage.innerHTML = `
          <p>Thank you for submitting your report. Your tracking ID is: 
          <span class="tracking-id">${trackingId}</span></p>
          <p>Please save this ID to track your report status.</p>
        `;
        successMessage.classList.remove('hidden');
        document.getElementById('errorMessage').classList.add('hidden');
        
        // Reset form
        document.getElementById('reportForm').reset();
        successMessage.scrollIntoView({ behavior: 'smooth' });
        
      } catch (error) {
        console.error('Error submitting report:', error);
        
        // Show error message
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.innerHTML = `
          <p>There was an error submitting your report. Please try again.</p>
          ${error.message ? `<p class="text-sm mt-1">Error: ${error.message}</p>` : ''}
        `;
        errorMessage.classList.remove('hidden');
        
      } finally {
        // Reset button state
        submitButton.disabled = false;
        buttonText.textContent = 'Submit';
        loadingSpinner.classList.add('hidden');
      }
    });
  </script>

  <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>
