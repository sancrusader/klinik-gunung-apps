import React from 'react';
import Sidebar from "@/Layouts/Dashboard/ParamedisSidebarLayout";
import { Head,usePage } from "@inertiajs/react";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import { Button } from "@/Components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/ui/card";
import { Printer } from 'lucide-react';

export default function Report({ totalPatients, sickPatientsCount, patients }) {

    const user = usePage().props.auth.user;
    return (
        <Sidebar header={'Laporan'}>
            <Head title="Laporan Paramedis" />
            <div className="p-6">
                <div className="flex justify-between items-center mb-6 no-print">
                    <h1 className="text-2xl font-bold">Laporan Pemeriksaan</h1>
                    <Button variant="outline">
                        <Printer className="mr-2 h-4 w-4" /> Download PDF
                    </Button>
                </div>

                <div className="space-y-6 no-print">
                    <div className="grid grid-cols-3 gap-4">
                        <Card>
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium">
                                    Total Pasien Diperiksa
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold">{totalPatients}</div>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium">
                                    Jumlah Pasien Sehat
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold">{sickPatientsCount}</div>
                            </CardContent>
                        </Card>
                        <Card>
                            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle className="text-sm font-medium">
                                    Jumlah Pasien Membutuhkan Dokter
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div className="text-2xl font-bold">{sickPatientsCount}</div>
                            </CardContent>
                        </Card>
                    </div>

                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nama Pasien</TableHead>
                                <TableHead>Tanggal Lahir</TableHead>
                                <TableHead>Status Kesehatan</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            {patients.map((patient, index) => (
                                <TableRow key={index}>
                                    <TableCell className="font-medium">{patient.name}</TableCell>
                                    <TableCell>{patient.date_of_birth}</TableCell>
                                    <TableCell>{patient.health_status}</TableCell>
                                </TableRow>
                            ))}
                        </TableBody>
                    </Table>
                </div>
            </div>
        </Sidebar>
    );
}

