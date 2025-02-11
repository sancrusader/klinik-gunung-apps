import React, { useState,useEffect } from 'react';
import AdminSidebar from "@/Layouts/Dashboard/AdminSidebarLayout";
import { Head, usePage } from '@inertiajs/react';
import { CircleCheck } from 'lucide-react';
import QuestionerHeader from './_components/table-header';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/Components/ui/table";
import EditQuestionModal from './Partials/Edit';
import {toast,Toaster} from "sonner"

export default function Index({ questions }) {
    const [questionList, setQuestionList] = useState(questions.data || []);
    const [isEditModalOpen, setIsEditModalOpen] = useState(false);
    const [currentQuestion, setCurrentQuestion] = useState(null);

    const handleEditClick = (question) => {
        setCurrentQuestion(question);
        setIsEditModalOpen(true);
    };

    const handleSaveEdit = (editedQuestion) => {
        setQuestionList(prevList =>
            prevList.map(q => q.id === editedQuestion.id ? editedQuestion : q)
        );
        setIsEditModalOpen(false);
    };


    const { flash } = usePage().props;
    useEffect(() => {
        if (flash.message) {
            toast(flash.message, {
                icon: <CircleCheck className="h-5 w-5 text-green-500"/>
            });
        }
    }, [flash.message]);

    return (
        <AdminSidebar header={'Daftar Kuesioner'}>
            <Head title="Daftar Kuesioner" />
            <Toaster position="top-center" />
            <QuestionerHeader
                title="Daftar Kuesioner"
                buttonText="Buat Kuesioner"
                routeName={route('questioner.create')}
            />
            <Table>
                <TableCaption>Daftar Kueosioner</TableCaption>
                <TableHeader>
                    <TableRow>
                        <TableHead>No</TableHead>
                        <TableHead>Pertanyaan</TableHead>
                        <TableHead>Tipe Jawaban</TableHead>
                        <TableHead>Opsi</TableHead>
                        <TableHead>Aksi</TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {questionList.map((question, index) => (
                        <TableRow key={question.id}>
                            <TableCell>{index + 1}</TableCell>
                            <TableCell>{question.question_text}</TableCell>
                            <TableCell>{question.answer_type}</TableCell>
                            <TableCell>{question.options ? question.options.join(', ') : '-'}</TableCell>
                            <TableCell>
                                <EditQuestionModal question={question} />
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </AdminSidebar>
    );
}

