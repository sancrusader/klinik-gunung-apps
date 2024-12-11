import React from 'react';
import { usePage,Head } from '@inertiajs/react';
import ManagerSidebar from "@/Layouts/Dashboard/ManagerSidebarLayout";

export default function Index() {
    const user = usePage().props.auth.user;

    return (
        <ManagerSidebar header={'Dashboard'}>
        <div>
            <h1>Welcome to my app {user.name}</h1>
        </div>
        </ManagerSidebar>
    );
}