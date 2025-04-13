export function Notes() {
    return (
      <section className="bg-white rounded p-4 shadow">
        <h2 className="text-xl font-semibold mb-2">Coach Notes</h2>
        <textarea className="w-full border p-2 rounded" placeholder="Write your coaching notes here..." rows={5}></textarea>
      </section>
    );
  }