export function PlayerPayments() {
    const payments = [
      { month: 'March', status: 'Paid' },
      { month: 'April', status: 'Pending' },
    ];
    return (
      <section className="bg-white rounded p-4 shadow">
        <h2 className="text-xl font-semibold mb-2">Payment History</h2>
        <table className="w-full">
          <thead><tr><th>Month</th><th>Status</th></tr></thead>
          <tbody>
            {payments.map((p, i) => (
              <tr key={i}><td>{p.month}</td><td>{p.status}</td></tr>
            ))}
          </tbody>
        </table>
      </section>
    );
  }