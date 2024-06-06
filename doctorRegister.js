const supabaseUrl = 'https://ogkmlmrjcvctjsxvwxex.supabase.co';
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im9na21sbXJqY3ZjdGpzeHZ3eGV4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MTU3MzcxNTksImV4cCI6MjAzMTMxMzE1OX0.NvXPXZPTgVkCIWIsXAVB84XxXEKyDi8fvq0YzEn_1zI';
const database = supabase.createClient(supabaseUrl, supabaseKey);
console.log(database);

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const firstName = document.getElementById('first_name').value;
        const lastName = document.getElementById('last_name').value;
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const location = document.getElementById('location').value;
        const speciality = document.getElementById('speciality').value;
        const phone = document.getElementById('phone').value;

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        try {
            const { user, error } = await database.auth.signUp({ email, password });
            if (error) throw error;

            const { data, error: insertError } = await database
                .from('doctors')
                .insert([
                    {
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        location: location,
                        speciality: speciality,
                        phone: phone
                    }
                ]);
            if (insertError) throw insertError;

            alert("Registration successful!");
            window.location.href = "LoginDoctor.html";
        } catch (error) {
            if (error.message.includes("rate limit")) {
                alert("Error: Rate limit exceeded. Please try again in a few minutes.");
            } else {
                alert(`Error: ${error.message}`);
            }
        }
    });
});
