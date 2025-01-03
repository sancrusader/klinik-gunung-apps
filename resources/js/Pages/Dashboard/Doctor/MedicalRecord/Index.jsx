'use client'

import React, { useState, useMemo } from 'react';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Button } from "@/Components/ui/button";
import { MoreHorizontal, FileText } from 'lucide-react';
import { Separator } from "@/Components/ui/separator";
import DoctorSidebar from "@/Layouts/Dashboard/DoctorSidebarLayout";
import { Input } from "@/Components/ui/input";
import { Head, Link } from "@inertiajs/react";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/ui/avatar";

export default function MedicalRecord({ medicalRecords }) {
    const [searchTerm, setSearchTerm] = useState('');

    const handleSearchChange = (event) => {
        setSearchTerm(event.target.value);
    };
    

    const filteredRecords = useMemo(() => {
        return medicalRecords.filter((record) => {
            const patientName = record.patient?.name?.toLowerCase() || '';
            const medicalNumber = record.medical_record_number?.toLowerCase() || '';
            const search = searchTerm.toLowerCase();
            return patientName.includes(search) || medicalNumber.includes(search);
        });
    }, [medicalRecords, searchTerm]);

    return (
        <DoctorSidebar>
            <Head title="Medical Record" />
            <div>
                <h1 className="text-2xl font-bold tracking-tight">
                    Medical Record
                </h1>
                <p className="text-muted-foreground">
                    Here&apos;s a list of medical records!
                </p>
            </div>
            <div className="my-4 flex items-end justify-between sm:my-0 sm:items-center">
                <div className="flex flex-col gap-4 sm:my-4 sm:flex-row">
                    <Input
                        placeholder="Search by Patient Name or Medical Number..."
                        className="h-9 w-40 lg:w-[250px]"
                        value={searchTerm}
                        onChange={handleSearchChange}
                    />
                </div>
            </div>
            <Separator className="shadow" />
            <ul className="faded-bottom no-scrollbar grid gap-4 overflow-auto pb-16 pt-4 md:grid-cols-2 lg:grid-cols-3">
                {filteredRecords.map((record) => (
                    <li key={record.id} className="rounded-lg border p-4 hover:shadow-md">
                        <div className="mb-8 flex items-center justify-between">
                            <Avatar className="flex size-10 items-center justify-center rounded-lg">
                                <AvatarImage
                                    src={`https://api.dicebear.com/6.x/initials/svg?seed=${record.patient?.name}`}
                                    alt={record.patient?.name}
                                />
                                <AvatarFallback>
                                    {record.patient?.name
                                        .split(" ")
                                        .map((n) => n[0])
                                        .join("")}
                                </AvatarFallback>
                            </Avatar>
                            <DropdownMenu>
                                <DropdownMenuTrigger asChild>
                                    <Button
                                        variant="ghost"
                                        className="h-8 w-8 p-0"
                                    >
                                        <span className="sr-only">
                                            Open menu
                                        </span>
                                        <MoreHorizontal className="h-4 w-4" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuLabel>Aksi</DropdownMenuLabel>
                                    <Link
                                        href={route(
                                            "medical-record.show",
                                            record.id
                                        )}
                                    >
                                        <DropdownMenuItem>
                                            <FileText className="mr-2 h-4 w-4" />
                                            <span>Lihat Detail</span>
                                        </DropdownMenuItem>
                                    </Link>

                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem>
                                        Edit Record
                                    </DropdownMenuItem>
                                    <DropdownMenuItem>
                                        Jadwalkan Tindak Lanjut
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                        <div>
                            <h2 className="mb-1 font-semibold">
                                {record.patient?.name ||
                                    "Pasien Tidak Ditemukan"}
                            </h2>
                            <p className="line-clamp-2 text-gray-500">
                                {record.medical_record_number || "N/A"}
                            </p>
                        </div>
                    </li>
                ))}
            </ul>
        </DoctorSidebar>
    );
}

