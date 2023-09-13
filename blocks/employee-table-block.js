const { registerBlockType } = wp.blocks;
const {
    element: { useState, useEffect },
} = wp;

const EmployeeListBlock = (props) => {
    const { attributes, setAttributes } = props;
    const { sortOrder, sortField } = attributes;

    const [employees, setEmployees] = useState([]);
    const [sortedEmployees, setSortedEmployees] = useState([]);

    useEffect(() => {
        fetch(`${wpApiSettings.root}wp/v2/employee`) // Include _embed to fetch embedded data
            .then((response) => response.json())
            .then((data) => setEmployees(data))
            .catch((error) => console.error(error));
    }, []);

    useEffect(()=> {
        // Function to sort employees based on the selected field and order
        const sortEmployees = () => {
            const sorted = [...employees];
            sorted.sort((a, b) => {
                if (sortOrder === 'asc') {
                    return a.meta[`_em_${sortField}`].localeCompare(b.meta[`_em_${sortField}`]);
                } else {
                    return b.meta[`_em_${sortField}`].localeCompare(a.meta[`_em_${sortField}`]);
                }
            });
            setSortedEmployees(sorted);
        };

        // Trigger sorting when the sorting field or order changes
        sortEmployees();
    }, [employees, sortOrder, sortField]);

    // Function to toggle sorting order
    const toggleSortOrder = () => {
        const newSortOrder = sortOrder === 'asc' ? 'desc' : 'asc';
        setAttributes({ sortOrder: newSortOrder });
    };
    // Function to change the sorting field
    const changeSortField = (field) => {
        setAttributes({ sortField: field });
    };
  

    return (
        <div>
            <h2>Employee List</h2>
            <button onClick={toggleSortOrder}>Toggle Sort Order ({sortOrder === 'asc' ? 'Ascending' : 'Descending'})</button>
            <table style={{ borderCollapse: 'collapse', width: '100%' }}>
                <thead>
                    <tr>
                        <th style={{ border: '2px solid #ddd' }} onClick={() => changeSortField('name')}>
                            Name {sortField === 'name' && <span>({sortOrder === 'asc' ? '↑' : '↓'})</span>}
                        </th>
                        <th style={{ border: '2px solid #ddd' }} onClick={() => changeSortField('email')}>
                            Email {sortField === 'email' && <span>({sortOrder === 'asc' ? '↑' : '↓'})</span>}
                        </th>
                        <th style={{ border: '2px solid #ddd' }} onClick={() => changeSortField('age')}>
                            Age {sortField === 'age' && <span>({sortOrder === 'asc' ? '↑' : '↓'})</span>}
                        </th>
                        <th style={{ border: '2px solid #ddd' }} onClick={() => changeSortField('date_of_hiring')}>
                            Date of Hiring {sortField === 'date_of_hiring' && <span>({sortOrder === 'asc' ? '↑' : '↓'})</span>}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    {sortedEmployees.map((employee) => (
                        <tr key={employee.id}>
                            <td style={{ border: '1px solid #ddd', textAlign: 'center' }}>{employee.meta._em_name}</td>
                            <td style={{ border: '1px solid #ddd', textAlign: 'center' }}>{employee.meta._em_email}</td>
                            <td style={{ border: '1px solid #ddd', textAlign: 'center' }}>{employee.meta._em_age}</td>
                            <td style={{ border: '1px solid #ddd', textAlign: 'center' }}>{employee.meta._em_date_of_hiring}</td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

registerBlockType('employee-manager/employee-list', {
    title: 'Employee List',
    icon: 'shield',
    category: 'common',
    attributes: {
        sortOrder: {
            type: 'string',
            default: 'asc',
        },
        sortField: {
            type: 'string',
            default: 'name',
        },
    },
    edit: EmployeeListBlock,
    save: () => {
         // Return a placeholder or empty content in the save function
         return null;
    },
});
