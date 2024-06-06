const supabaseUrl = 'https://ogkmlmrjcvctjsxvwxex.supabase.co';
const supabaseKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Im9na21sbXJqY3ZjdGpzeHZ3eGV4Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3MTU3MzcxNTksImV4cCI6MjAzMTMxMzE1OX0.NvXPXZPTgVkCIWIsXAVB84XxXEKyDi8fvq0YzEn_1zI';
const database = supabase.createClient(supabaseUrl, supabaseKey);
console.log(database);

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    if (!form) {
        console.error('Form not found');
        return;
    }

    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const firstNameInput = form.querySelector('input[placeholder="First Name"]');
        if (!firstNameInput) {
            console.error('First Name input not found');
            return;
        }
        const firstName = firstNameInput.value;
        console.log('First Name:', firstName);

        const lastNameInput = form.querySelector('input[placeholder="Last Name"]');
        if (!lastNameInput) {
            console.error('Last Name input not found');
            return;
        }
        const lastName = lastNameInput.value;
        console.log('Last Name:', lastName);

        const emailInput = form.querySelector('input[type="email"]');
        if (!emailInput) {
            console.error('Email input not found');
            return;
        }
        const email = emailInput.value;
        console.log('Email:', email);

        const passwordInput = form.querySelector('input[type="password"]');
        const confirmPasswordInput = form.querySelectorAll('input[type="password"]')[1];
        if (!passwordInput || !confirmPasswordInput) {
            console.error('Password inputs not found');
            return;
        }
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        console.log('Password:', password);
        console.log('Confirm Password:', confirmPassword);

        const locationInput = form.querySelector('input[placeholder="Location"]');
        if (!locationInput) {
            console.error('Location input not found');
            return;
        }
        const location = locationInput.value;
        console.log('Location:', location);

        const phoneInput = form.querySelector('input[placeholder="Phone Number"]');
        if (!phoneInput) {
            console.error('Phone Number input not found');
            return;
        }
        const phone = phoneInput.value;
        console.log('Phone Number:', phone);

        const genderInput = form.querySelector('select');
        if (!genderInput) {
            console.error('Gender input not found');
            return;
        }
        const gender = genderInput.value;
        console.log('Gender:', gender);

        const ageInput = form.querySelector('input[placeholder="Age"]');
        if (!ageInput) {
            console.error('Age input not found');
            return;
        }
        const age = ageInput.value;
        console.log('Age:', age);

        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        try {
            const { data: user, error: signUpError } = await database.auth.signUp({ email, password });
            if (signUpError) throw signUpError;

            if (!user) {
                console.error('User not returned from signUp:', user);
                alert("Registration failed. Please try again.");
                return;
            }

            const { data, insertError } = await database
                .from('Patients')
                .insert([
                    {
                        id: user.id,
                        created_at: new Date().toISOString(),
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        password: password,
                        location: location,
                        phone: phone,
                        gender: gender,
                        age: age
                    }
                ]);

            if (insertError) throw insertError;

            alert("Registration successful!");
            window.location.href = "LoginPatient.html";
        } catch (error) {
            if (error.message.includes("rate limit")) {
                alert("Error: Rate limit exceeded. Please try again in a few minutes.");
            } else {
                alert(`Error: ${error.message}`);
            }
        }
    });
});
