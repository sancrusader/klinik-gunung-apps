import ManagerSidebar from "@/Layouts/Dashboard/ManagerSidebarLayout";
import { Head } from "@inertiajs/react";
import MedicalHeader from "../_components/table-header";
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";

export default function Index({ users }) {
    return (
        <ManagerSidebar header={"Daftar Tenaga Medis"}>
            <Head title="Daftar Tenaga Medis" />
            <MedicalHeader/>
            <Table>
                <TableCaption>Daftar Tenaga Medis</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>Nama Tenaga Medis</TableHead>
                        <TableHead>Email</TableHead>
                        <TableHead>Nik</TableHead>
                        <TableHead>Telepon</TableHead>
                        <TableHead>Peran</TableHead>
                        <TableHead>Tanggal Dibuat</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {users.map((user) => (
                        <TableRow key={user.id}>
                            <TableCell>{user.name}</TableCell>
                            <TableCell>{user.email}</TableCell>
                            <TableCell>{user.nik}</TableCell>
                            <TableCell>{user.phone}</TableCell>
                            <TableCell>{user.role}</TableCell>
                            <TableCell>
                                {new Date(user.created_at).toLocaleDateString()}
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </ManagerSidebar>
    );
}
