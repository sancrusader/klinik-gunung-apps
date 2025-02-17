import React from "react";

const AuthLayout = ({ children }) => {
    return (
        <section className="flex items-center justify-center h-screen">
                {children}
        </section>
    );
};

export default AuthLayout;
