const supabaseUrl = 'https://ogkmlmrjcvctjsxvwxex.supabase.co';
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im9na21sbXJqY3ZjdGpzeHZ3eGV4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MTU3MzcxNTksImV4cCI6MjAzMTMxMzE1OX0.NvXPXZPTgVkCIWIsXAVB84XxXEKyDi8fvq0YzEn_1zI';
const database = supabase.createClient(supabaseUrl, supabaseKey);

document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.querySelector('form');
    const emailInput = loginForm.querySelector('input[type="email"]');
    const passwordInput = loginForm.querySelector('input[type="password"]');  
    console.log(emailInput);  // Check what this selector returns
console.log(passwordInput);  // Check what this selector returns

    loginForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        
        const email = emailInput.value;
        const password = passwordInput.value;

        if (!email || !password) {
            alert('Please enter both email and password');
            return;
        }

        // Authenticate the user
        const { data, error } = await database.auth.signInWithPassword({
            email,
            password,
        });

        if (error) {
            alert('Login failed: ' + error.message);
        } else {
            alert('Login successful!');
            // Redirect to dashboard
            window.location.href = 'DoctorDashboard.html';
        }
    });
});
