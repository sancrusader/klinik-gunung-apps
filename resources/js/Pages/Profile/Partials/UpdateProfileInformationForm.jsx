import { useState } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import { Button } from "@/Components/ui/button"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar"
import { AlertCircle, Upload, X, CheckCircle2 } from 'lucide-react';
import { Alert, AlertDescription, AlertTitle } from "@/Components/ui/alert";
import { toast, Toaster } from "sonner";

export default function UpdateProfileInformation({ mustVerifyEmail, status }) {
    const user = usePage().props.auth.user;
    const [photoPreview, setPhotoPreview] = useState(user?.avatar
        ? user.avatar.startsWith("http")
            ? user.avatar
            : `/storage/${user.avatar}`
        : "/storage/avatar/avatar.jpg" || null);

    const { data, setData, post, errors, processing } = useForm({
        name: user.name,
        email: user.email,
        avatar: null,
    });

    const submit = (e) => {
        e.preventDefault();

        // Gunakan FormData untuk mengirim data dan file
        const formData = new FormData();
        formData.append('name', data.name);
        formData.append('email', data.email);
        if (data.avatar) {
            formData.append('avatar', data.avatar);
        }

        post(route("profile.update"), {
            data: formData,
            onSuccess: () => {
                toast.success("Profile berhasil diperbarui", {
                    icon: <CheckCircle2 className="h-5 w-5 text-green-500" />,
                });
            },
            onError: (errors) => {
                const errorMessage = Object.values(errors)[0] || "Terjadi kesalahan.";
                toast.error(errorMessage, {
                    icon: <X className="h-5 w-5 text-red-500" />,
                });
            },
            preserveScroll: true,
            forceFormData: true, // Pastikan Inertia memahami format FormData
        });
    };

    const handlePhotoChange = (e) => {
        const file = e.target.files[0];
        if (file) {
            setData('avatar', file);
            setPhotoPreview(URL.createObjectURL(file)); // Tampilkan pratinjau gambar
        }
    };

    return (
        <form onSubmit={submit} className="space-y-6">
            <Toaster position="top-center" />
            <div className="flex items-center space-x-4">
                <Avatar className="w-20 h-20">
                    <AvatarImage src={photoPreview} alt={user.name} />
                    <AvatarFallback>{user.name.charAt(0)}</AvatarFallback>
                </Avatar>
                <div>
                    <Input
                        type="file"
                        onChange={handlePhotoChange}
                        className="hidden"
                        id="avatar"
                        accept="image/*"
                    />
                    <Button type="button" variant="outline" onClick={() => document.getElementById('avatar').click()}>
                        <Upload className="w-4 h-4 mr-2" />
                        Change Photo
                    </Button>
                </div>
            </div>

            <div>
                <Label htmlFor="name">Name</Label>
                <Input
                    id="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                />
                {errors.name && <p className="text-sm text-red-600 mt-1">{errors.name}</p>}
            </div>

            <div>
                <Label htmlFor="email">Email</Label>
                <Input
                    id="email"
                    type="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                />
                {errors.email && <p className="text-sm text-red-600 mt-1">{errors.email}</p>}
            </div>

            {mustVerifyEmail && user.email_verified_at === null && (
                <Alert variant="destructive">
                    <AlertCircle className="h-4 w-4" />
                    <AlertTitle>Unverified Email</AlertTitle>
                    <AlertDescription>
                        Your email address is unverified.
                        <Button variant="link" className="p-0 h-auto font-normal" onClick={() => route('verification.send')}>
                            Click here to re-send the verification email.
                        </Button>
                    </AlertDescription>
                </Alert>
            )}

            <Button type="submit" disabled={processing}>Save</Button>

            {status === 'profile-updated' && (
                <p className="text-sm text-green-600">Saved.</p>
            )}
        </form>
    );
}
